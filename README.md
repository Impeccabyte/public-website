# Impeccabyte marketing site (Drupal 11 in Docker)

The Impeccabyte marketing site, built from the approved wireframes in `design/wireframes/`.

## What is here

| Path | What it is |
|---|---|
| `docker-compose.yml`, `docker/Dockerfile`, `docker/php.ini` | Drupal 11 (PHP 8.4, Apache) and MariaDB |
| `scripts/setup.sh` | Builds, starts and installs the site from `config/sync`, then adds the first content. Safe to run again. |
| `scripts/drush` | Runs drush inside the container |
| `scripts/setup/` | Re-runnable scripts that build the site's structure, one per build phase |
| `scripts/content/` | Re-runnable scripts that add the first content |
| `scripts/favicon/build-icons.py` | Builds the favicon and touch icon from the logo mark |
| `scripts/claude-build.sh` | Opens Claude Code with the build prompt loaded |
| `CLAUDE.md` | Standing project rules Claude Code reads every session |
| `docs/BUILD_BRIEF.md` | The build plan: content model, page mapping, phases |
| `docs/KICKOFF_PROMPT.md` | The prompt that started the build |
| `design/wireframes/` | Source of the 9 screens plus header, footer and mobile boards |
| `web/themes/custom/impeccabyte/` | The theme: design tokens and one single directory component per design system component or page section |
| `web/modules/custom/impeccabyte_site/` | Site settings (show prices, contact details, disclosure), the recommended sort and the lead handlers |
| `web/modules/custom/impeccabyte_compare/` | The device compare toggle, tray and comparison page |
| `config/sync/` | Exported Drupal configuration |
| `private/` | Uploaded statements, local only, not committed |

## Quick start

You need Docker Desktop (or Docker Engine with Compose v2).

```bash
./scripts/setup.sh
```

The site runs at the port in `.env` (`WEB_PORT`, 8080 unless you change it). `setup.sh` prints a one-time admin login link and stores generated passwords in `.env`.

## Everyday commands

```bash
docker compose up -d        # start
docker compose down         # stop (data is kept)
./scripts/drush cr          # clear caches
./scripts/drush cex -y      # export config to config/sync
./scripts/drush uli         # new admin login link
docker compose down -v      # stop and delete the database and files (start over)
```

## Changing the site

Structure changes (content types, fields, views, forms) go in a script under `scripts/setup/`, and first content in a script under `scripts/content/`. Both kinds leave existing things alone, so they can be run again at any time:

```bash
./scripts/drush php:script scripts/setup/40-devices.php
./scripts/drush cex -y
```

A script only creates what is missing. To rebuild a view or form from its script, delete it first, run the script, then export. After a re-run, check `git status` and revert any URL pattern (`pathauto.pattern.*`) whose only change is its UUID.

## Prices

Pricing is not public yet. Device and setup prices are stored as numbers, and the "Show prices" setting at `/admin/config/system/impeccabyte` is off. While it is off every price shows "Pricing coming soon" with a Get a quote link, price sorts are hidden and the savings estimator is replaced by a quote prompt. Turn it on once pricing is final.

## Leads

Every form saves its submissions in Drupal (`/admin/structure/webform`). Each also has an Intercom handler and an Apollo placeholder, both off.

To turn Intercom on, put the access token in `.env` as `INTERCOM_ACCESS_TOKEN` and recreate the container, or add this to `web/sites/default/settings.local.php` inside the container:

```php
$settings['impeccabyte_intercom']['access_token'] = '...';
```

Then enable the "Intercom lead" handler on each form. The token is never stored in config. The Apollo handler does nothing yet; that account does not exist.

## Before this goes anywhere public

This setup is for local development. It has not been hardened for production: no HTTPS, no backups, no mail delivery, and the web container can write to its own code. Fill every `[bracketed]` placeholder, replace the placeholder testimonials and customer stories with real, approved ones, self-host the fonts, supply the legal pages, and have the disclosure and pricing copy reviewed.
