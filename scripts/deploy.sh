#!/usr/bin/env bash
# Applies the checked-out code to the running production site. Run it on the
# server from the project folder, after pulling:
#
#   git pull --ff-only && scripts/deploy.sh
#
# GitHub Actions does exactly that on every push to main (see
# .github/workflows/deploy.yml and docs/DEPLOY.md). The pull happens before
# this script starts, so a deploy always runs the newest copy of it.
#
# Steps: back up the database, rebuild and start the containers, apply
# database updates and the exported config, run every content script (each
# one only changes content that still holds old wording, so edits made in the
# admin are kept), reindex search, clear caches and check the home page.
set -euo pipefail
cd "$(dirname "$0")/.."

[ -f .env ] || { echo "No .env here. Run scripts/server/bootstrap.sh first."; exit 1; }
set -a; . ./.env; set +a

BACKUP_DIR="${BACKUP_DIR:-/srv/impeccabyte-backups}"
KEEP_BACKUPS="${KEEP_BACKUPS:-30}"
DRUSH="./scripts/drush"
stamp="$(date -u +%Y%m%d-%H%M%S)"

echo "Deploying $(git rev-parse --short HEAD): $(git log -1 --format=%s)"

# 1. Back up the database before anything changes.
mkdir -p "$BACKUP_DIR"
if $DRUSH status --field=bootstrap 2>/dev/null | grep -q Successful; then
  $DRUSH sql:dump | gzip > "$BACKUP_DIR/db-$stamp-predeploy.sql.gz"
  echo "Database backed up to $BACKUP_DIR/db-$stamp-predeploy.sql.gz"
fi

# 2. Rebuild the image if the Dockerfile changed, and start everything.
# The network Caddy shares with the CRM (docker-compose.prod.yml). Harmless
# where the production file is not loaded.
docker network inspect edge >/dev/null 2>&1 || docker network create edge >/dev/null
docker compose up -d --build --remove-orphans

# 3. Wait for Drupal to answer drush.
for _ in $(seq 1 60); do
  $DRUSH status --field=bootstrap 2>/dev/null | grep -q Successful && break
  sleep 2
done

# 4. Database updates, then the structure from config/sync.
$DRUSH updb -y
$DRUSH cim -y

# 5. Content scripts, in order. Each is safe to re-run.
for script in scripts/content/*.php; do
  echo "Running ${script}..."
  $DRUSH php:script "$script"
done

# 6. Search index and caches.
$DRUSH search-api:index 2>/dev/null || true
$DRUSH cr

# 7. The home page must answer. Caddy's lock is not in this path, because
#    this asks Drupal directly on the server.
port="${WEB_PORT##*:}"
code="$(curl -s -o /dev/null -w '%{http_code}' "http://127.0.0.1:${port}/")"
if [ "$code" != "200" ]; then
  echo "Home page answered $code after the deploy. Check ./scripts/drush watchdog:show."
  exit 1
fi

# 8. Keep the newest backups only.
ls -1t "$BACKUP_DIR"/db-*.sql.gz 2>/dev/null | tail -n +"$((KEEP_BACKUPS + 1))" | xargs -r rm -f

echo "Deployed $(git rev-parse --short HEAD). Home page answered 200."
