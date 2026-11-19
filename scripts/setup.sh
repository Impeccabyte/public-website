#!/usr/bin/env bash
# One-time (and safely repeatable) setup: build the image, start the stack,
# install Drupal, turn on the modules and the Impeccabyte theme.
set -euo pipefail
cd "$(dirname "$0")/.."

command -v docker >/dev/null || { echo "Docker is not installed or not on PATH."; exit 1; }
docker compose version >/dev/null 2>&1 || { echo "Docker Compose v2 is required (docker compose)."; exit 1; }

rand() { LC_ALL=C tr -dc 'A-Za-z0-9' </dev/urandom | head -c 24; }

if [ ! -f .env ]; then
  cp .env.example .env
  for key in DB_PASSWORD DB_ROOT_PASSWORD ADMIN_PASSWORD; do
    val="$(rand)"
    # portable in-place edit (macOS and Linux)
    sed "s|^${key}=.*|${key}=${val}|" .env > .env.tmp && mv .env.tmp .env
  done
  echo "Created .env with generated passwords."
fi
set -a; . ./.env; set +a

echo "Building and starting containers..."
docker compose up -d --build

DRUSH="docker compose exec -T drupal /opt/drupal/vendor/bin/drush"

FRESH=0
if $DRUSH status --field=bootstrap 2>/dev/null | grep -q Successful; then
  echo "Drupal is already installed. Skipping site install."
else
  FRESH=1
  docker compose exec -T drupal bash -c 'chmod u+w web/sites/default 2>/dev/null || true'
  if [ -f config/sync/core.extension.yml ]; then
    # The exported configuration is the site's structure: install from it.
    echo "Installing Drupal from config/sync..."
    $DRUSH site:install --existing-config -y \
      --db-url="mysql://${DB_USER}:${DB_PASSWORD}@db:3306/${DB_NAME}" \
      --account-name="${ADMIN_USER}" \
      --account-pass="${ADMIN_PASSWORD}" \
      --account-mail="${ADMIN_MAIL}"
  else
    echo "Installing Drupal..."
    $DRUSH site:install standard -y \
      --db-url="mysql://${DB_USER}:${DB_PASSWORD}@db:3306/${DB_NAME}" \
      --site-name="${SITE_NAME}" \
      --account-name="${ADMIN_USER}" \
      --account-pass="${ADMIN_PASSWORD}" \
      --account-mail="${ADMIN_MAIL}"
  fi
  # Keep exported config in ./config/sync so it is versioned with the project.
  docker compose exec -T drupal bash -c '
    f=web/sites/default/settings.php
    chmod u+w web/sites/default "$f"
    grep -q "config_sync_directory.*config/sync" "$f" || echo "\$settings[\"config_sync_directory\"] = \"../config/sync\";" >> "$f"
    mkdir -p web/sites/default/files && chown -R www-data:www-data web/sites/default/files'
fi

# Private files (form uploads) live outside the web root, and settings.local.php
# is loaded when present so secrets such as the Intercom token stay out of config.
docker compose exec -T drupal bash -c '
  f=web/sites/default/settings.php
  chmod u+w web/sites/default "$f"
  grep -q "^\$settings\[.file_private_path" "$f" || echo "\$settings[\"file_private_path\"] = \"../private\";" >> "$f"
  grep -q "^if (file_exists(\$app_root" "$f" || cat >> "$f" <<"PHP"
if (file_exists($app_root . "/" . $site_path . "/settings.local.php")) {
  include $app_root . "/" . $site_path . "/settings.local.php";
}
PHP
  mkdir -p private && chown www-data:www-data private 2>/dev/null || true'

if [ -f config/sync/core.extension.yml ]; then
  # Structure comes from config/sync. The setup scripts built it and are kept
  # for the record and for changes; they do not need to run on an install.
  if [ "$FRESH" = "1" ]; then
    # Home is the front page and other settings point at content, so the
    # first content has to exist right after an install from config.
    for script in scripts/content/*.php; do
      echo "Running ${script}..."
      $DRUSH php:script "$script"
    done
  fi
else
  echo "Enabling modules..."
  $DRUSH en -y paragraphs entity_reference_revisions webform webform_ui pathauto metatag \
    admin_toolbar admin_toolbar_tools twig_tweak media media_library \
    search_api search_api_db facets better_exposed_filters svg_image simple_sitemap redirect
  echo "Enabling the Impeccabyte theme..."
  $DRUSH theme:enable impeccabyte -y
  $DRUSH config:set system.theme default impeccabyte -y
  for script in scripts/setup/*.php scripts/content/*.php; do
    case "$(basename "$script")" in _*) continue;; esac
    echo "Running ${script}..."
    $DRUSH php:script "$script"
  done
fi
$DRUSH search-api:index 2>/dev/null || true
$DRUSH cr

echo
echo "Done. Site: http://localhost:${WEB_PORT:-8080}"
echo "One-time admin login link:"
$DRUSH uli --uri="http://localhost:${WEB_PORT:-8080}"
