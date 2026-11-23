# Deploying to production

Production runs on a Vultr server (45.76.238.138) with the same Docker stack as local, plus Caddy in front. Every push to `main` deploys automatically.

## How a deploy works

1. A push to `main` starts `.github/workflows/deploy.yml`.
2. The workflow connects to the server as `deploy` with a key that can only run one command: `git pull --ff-only`, then `scripts/deploy.sh`.
3. `scripts/deploy.sh`:
   - backs up the database
   - rebuilds and starts the containers
   - runs `drush updb` and `drush cim`
   - runs every script in `scripts/content/` in order
   - reindexes search and clears caches
   - fails the deploy if the home page does not answer 200

The content scripts only change content that still holds the wording they replace, so edits made in the production admin are kept. Each one is safe to run again, which is why a deploy runs them all.

Watch deploys under the repository's Actions tab. You can also re-run one there ("Run workflow").

## Rules that follow from this

- **Config belongs to the repo.**
  - Every deploy imports `config/sync`, so a settings change made only in the production admin is undone by the next deploy.
  - Make structure and settings changes locally, export them with `./scripts/drush cex -y`, and commit them.
- **Content belongs to production.**
  - Pages, text and photos edited in the production admin stay as they are.
  - A local database is never copied up.
- **Never add the show prices override on the server.** Prices go live when "Show prices" is ticked in exported config, as the README describes.

## The domain and HTTPS

The site is served at https://impeccabyte.com and https://www.impeccabyte.com. Both point at the server, and Caddy gets and renews the certificates on its own. These are set in `/srv/impeccabyte/.env`:
- `SITE_ADDRESS="impeccabyte.com, www.impeccabyte.com"`
- `SITE_HOSTS="impeccabyte.com www.impeccabyte.com 45.76.238.138"`
- `SITE_URL=https://impeccabyte.com`: the address used for links built outside a web request, such as the XML sitemap. Without it, Drupal writes `http://default`.

When you change either value:
1. Re-run `scripts/server/bootstrap.sh` as root. It rewrites Drupal's trusted host names in `web/sites/default/settings.local.php` from `SITE_HOSTS`.
2. Run `docker compose up -d`.

## The site lock

The site is open (`SITE_LOCK=off`). To put a password on every page and turn off search indexing, for example while something is being fixed:
1. Set `SITE_LOCK=on` in `/srv/impeccabyte/.env`.
2. Run `docker compose up -d` in `/srv/impeccabyte` as `deploy`.

The user is `preview`. The password is in `/root/impeccabyte-credentials.txt` on the server, along with the Drupal admin password.

## Backups

- `scripts/deploy.sh` dumps the database before every deploy.
- `scripts/server/backup.sh` runs nightly at 09:15 UTC and saves the database and the uploaded files.
- Both write to `/srv/impeccabyte-backups` and keep the newest 30 of each.
- These copies live on the same server. For off-server copies, add Vultr's automatic backups or copy the folder elsewhere.

## Setting up a server from scratch

On a server with Docker (Compose 2.24.4 or newer), as root:

```bash
curl -fsSL https://raw.githubusercontent.com/Impeccabyte/public-website/main/scripts/server/bootstrap.sh -o bootstrap.sh
SITE_ADDRESS="impeccabyte.com, www.impeccabyte.com" SITE_HOSTS="impeccabyte.com www.impeccabyte.com 45.76.238.138" ACTIONS_PUBKEY="ssh-ed25519 AAAA... github-actions-deploy" bash bootstrap.sh
```

Then set the repository secrets:
- `DEPLOY_HOST`: the server's address.
- `DEPLOY_SSH_KEY`: the private half of the Actions key.
- `DEPLOY_KNOWN_HOSTS`: the output of `ssh-keyscan -t ed25519 <host>`.

## Not set up yet

- **Mail delivery:** Drupal cannot send email from the server, so form notification emails are not delivered. Leads still save to each form's submissions.
- **Intercom:** `INTERCOM_ACCESS_TOKEN` is empty in the production `.env`.
- **Self-hosted fonts and the README's other launch items:** see "Before this goes anywhere public" in the README.
