#!/usr/bin/env bash
# One-time setup of the production server. Run it as root on a server that
# has Docker, from anywhere:
#
#   SITE_ADDRESS=":80" SITE_HOSTS="45.76.238.138" \
#   ACTIONS_PUBKEY="ssh-ed25519 AAAA... github-actions-deploy" \
#   bash bootstrap.sh
#
# It creates a "deploy" user, opens only SSH, HTTP and HTTPS in the firewall,
# clones the repo to /srv/impeccabyte, writes a production .env with new
# passwords, installs the site from config/sync and the content scripts,
# points Drupal at the Caddy proxy, schedules nightly backups and lets the
# GitHub Actions key run the deploy and nothing else.
#
# Safe to run again: it skips what is already in place and never replaces an
# existing .env or database.
#
# The admin and site lock passwords are written to
# /root/impeccabyte-credentials.txt (readable by root only), never printed.
set -euo pipefail

REPO_URL="${REPO_URL:-https://github.com/Impeccabyte/public-website.git}"
APP_DIR="${APP_DIR:-/srv/impeccabyte}"
BACKUP_DIR="${BACKUP_DIR:-/srv/impeccabyte-backups}"
DEPLOY_USER="${DEPLOY_USER:-deploy}"
# ":80" serves plain HTTP on the IP. Use "impeccabyte.com, www.impeccabyte.com"
# once DNS points here, for HTTPS.
SITE_ADDRESS="${SITE_ADDRESS:-:80}"
# Host names Drupal answers to, separated by spaces.
SITE_HOSTS="${SITE_HOSTS:-}"
ADMIN_MAIL="${ADMIN_MAIL:-howdy@impeccabyte.com}"
ACTIONS_PUBKEY="${ACTIONS_PUBKEY:-}"
CREDENTIALS=/root/impeccabyte-credentials.txt

[ "$(id -u)" = 0 ] || { echo "Run this as root."; exit 1; }
command -v docker >/dev/null || { echo "Docker is not installed."; exit 1; }
compose_version="$(docker compose version --short | sed 's/^v//')"
if [ "$(printf '%s\n' 2.24.4 "$compose_version" | sort -V | head -1)" != 2.24.4 ]; then
  echo "Docker Compose $compose_version is too old; 2.24.4 or newer is needed."; exit 1
fi
# Reads a fixed amount of randomness so no command in the pipe is cut off
# early, which would fail the script under pipefail.
rand() { head -c 512 /dev/urandom | LC_ALL=C tr -dc 'A-Za-z0-9' | cut -c1-"${1:-24}"; }

# 1. The deploy user, allowed to run Docker.
id "$DEPLOY_USER" >/dev/null 2>&1 || useradd --create-home --shell /bin/bash "$DEPLOY_USER"
usermod -aG docker "$DEPLOY_USER"
apt-get install -y -qq git curl >/dev/null 2>&1 || true

# 2. Firewall: SSH, HTTP and HTTPS only.
if command -v ufw >/dev/null; then
  ufw allow OpenSSH >/dev/null
  ufw allow 80/tcp >/dev/null
  ufw allow 443/tcp >/dev/null
  ufw --force enable >/dev/null
fi

# 3. The code.
mkdir -p "$APP_DIR" "$BACKUP_DIR"
chown "$DEPLOY_USER:$DEPLOY_USER" "$APP_DIR" "$BACKUP_DIR"
if [ ! -d "$APP_DIR/.git" ]; then
  sudo -u "$DEPLOY_USER" git clone --branch main "$REPO_URL" "$APP_DIR"
fi
cd "$APP_DIR"

# 4. The production .env, written once.
if [ ! -f .env ]; then
  admin_password="$(rand 24)"
  lock_password="$(rand 16)"
  lock_hash="$(docker run --rm caddy:2.8 caddy hash-password --plaintext "$lock_password")"
  umask 077
  cat > .env <<ENV
COMPOSE_PROJECT_NAME=impeccabyte
COMPOSE_FILE=docker-compose.yml:docker-compose.prod.yml
WEB_PORT=127.0.0.1:8080
DB_NAME=drupal
DB_USER=drupal
DB_PASSWORD=$(rand 32)
DB_ROOT_PASSWORD=$(rand 32)
SITE_NAME=Impeccabyte
ADMIN_USER=admin
ADMIN_PASSWORD=${admin_password}
ADMIN_MAIL=${ADMIN_MAIL}
INTERCOM_ACCESS_TOKEN=
SITE_ADDRESS="${SITE_ADDRESS}"
SITE_HOSTS="${SITE_HOSTS}"
SITE_LOCK=on
SITE_LOCK_USER=preview
SITE_LOCK_HASH='${lock_hash}'
BACKUP_DIR=${BACKUP_DIR}
ENV
  chown "$DEPLOY_USER:$DEPLOY_USER" .env
  cat > "$CREDENTIALS" <<CREDS
Impeccabyte production, written $(date -u +%Y-%m-%d) by scripts/server/bootstrap.sh.

Site lock (until launch): user "preview", password ${lock_password}
Drupal admin: user "admin", password ${admin_password}
CREDS
  chmod 600 "$CREDENTIALS"
  umask 022
fi

# 5. Install the site (setup.sh skips the install when it is already done).
sudo -u "$DEPLOY_USER" ./scripts/setup.sh > /root/impeccabyte-setup.log 2>&1 || { echo "setup.sh failed; see /root/impeccabyte-setup.log"; exit 1; }

# 6. Drupal sits behind Caddy: trust it for HTTPS and client addresses, and
#    answer only to this site's host names.
set -a; . ./.env; set +a
hosts_php=""
for host in $SITE_HOSTS localhost 127.0.0.1; do
  hosts_php+="  '^$(printf '%s' "$host" | sed 's/\./\\\\./g')\$',"$'\n'
done
sudo -u "$DEPLOY_USER" docker compose exec -T drupal bash -c "cat > web/sites/default/settings.local.php" <<PHP
<?php

// Production only, written by scripts/server/bootstrap.sh. Never add the
// show_prices override here.

// Caddy is the only client that reaches Drupal from outside the server.
\$settings['reverse_proxy'] = TRUE;
\$settings['reverse_proxy_addresses'] = [\$_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'];

\$settings['trusted_host_patterns'] = [
${hosts_php}];
PHP
sudo -u "$DEPLOY_USER" ./scripts/drush cr >/dev/null

# 7. Nightly backups at 09:15 UTC (03:15 in Austin).
cron_line="15 9 * * * cd $APP_DIR && ./scripts/server/backup.sh >> $BACKUP_DIR/backup.log 2>&1"
{ { crontab -u "$DEPLOY_USER" -l 2>/dev/null || true; } | { grep -v 'scripts/server/backup.sh' || true; }; echo "$cron_line"; } | crontab -u "$DEPLOY_USER" -

# 8. The GitHub Actions key may only pull and deploy.
if [ -n "$ACTIONS_PUBKEY" ]; then
  home="$(getent passwd "$DEPLOY_USER" | cut -d: -f6)"
  install -d -m 700 -o "$DEPLOY_USER" -g "$DEPLOY_USER" "$home/.ssh"
  keys="$home/.ssh/authorized_keys"
  touch "$keys"
  if ! grep -qF "$ACTIONS_PUBKEY" "$keys"; then
    echo "command=\"cd $APP_DIR && git pull --ff-only -q && ./scripts/deploy.sh\",no-port-forwarding,no-agent-forwarding,no-X11-forwarding,no-pty $ACTIONS_PUBKEY" >> "$keys"
  fi
  chown "$DEPLOY_USER:$DEPLOY_USER" "$keys"
  chmod 600 "$keys"
fi

echo "Bootstrap done. Site: ${SITE_ADDRESS}. Passwords are in $CREDENTIALS."
