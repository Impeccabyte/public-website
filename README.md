# Impeccabyte marketing site (Drupal 11 in Docker)

A starter kit for building the Impeccabyte marketing site from the approved wireframes.

## What is here

| Path | What it is |
|---|---|
| `docker-compose.yml`, `docker/Dockerfile` | Drupal 11 (PHP 8.4, Apache) and MariaDB |
| `scripts/setup.sh` | Builds, starts and installs the site. Safe to run again. |
| `scripts/drush` | Runs drush inside the container |
| `scripts/claude-build.sh` | Opens Claude Code with the build prompt loaded |
| `CLAUDE.md` | Standing project rules Claude Code reads every session |
| `docs/BUILD_BRIEF.md` | The build plan: content model, page mapping, phases |
| `docs/KICKOFF_PROMPT.md` | The prompt that starts the build |
| `design/wireframes/` | Source of the 9 screens plus header, footer and mobile boards |
| `web/themes/custom/impeccabyte/` | Starter theme with design tokens and accessibility rules |
| `config/sync/` | Exported Drupal configuration (empty until the build exports it) |

## Quick start

You need Docker Desktop (or Docker Engine with Compose v2) and Claude Code.

```bash
./scripts/setup.sh          # about 5 to 10 minutes the first time
./scripts/claude-build.sh   # starts the build in Claude Code
```

The site runs at http://localhost:8080. `setup.sh` prints a one-time admin login link and stores generated passwords in `.env`.

To resume later at a given phase: `./scripts/claude-build.sh 3`.

## Everyday commands

```bash
docker compose up -d        # start
docker compose down         # stop (data is kept)
./scripts/drush cr          # clear caches
./scripts/drush cex -y      # export config to config/sync
./scripts/drush uli         # new admin login link
docker compose down -v      # stop and delete the database and files (start over)
```

## Before this goes anywhere public

This kit is for local development. It has not been hardened for production: no HTTPS, no backups, no mail delivery, and the web container can write to its own code. Fill every `[bracketed]` placeholder, replace the placeholder testimonials with real, approved ones, self-host the fonts, and have the disclosure and pricing copy reviewed.
