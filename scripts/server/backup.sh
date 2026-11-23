#!/usr/bin/env bash
# Nightly backup on the production server: the database and the uploaded
# files (public files, settings and private uploads). bootstrap.sh schedules
# it with cron for the deploy user. Keeps the newest KEEP_BACKUPS of each.
set -euo pipefail
cd "$(dirname "$0")/../.."
set -a; . ./.env; set +a

BACKUP_DIR="${BACKUP_DIR:-/srv/impeccabyte-backups}"
KEEP_BACKUPS="${KEEP_BACKUPS:-30}"
stamp="$(date -u +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_DIR"

./scripts/drush sql:dump | gzip > "$BACKUP_DIR/db-$stamp-nightly.sql.gz"
docker compose exec -T drupal tar -czf - -C /opt/drupal web/sites private > "$BACKUP_DIR/files-$stamp.tar.gz"

ls -1t "$BACKUP_DIR"/db-*.sql.gz | tail -n +"$((KEEP_BACKUPS + 1))" | xargs -r rm -f
ls -1t "$BACKUP_DIR"/files-*.tar.gz | tail -n +"$((KEEP_BACKUPS + 1))" | xargs -r rm -f
