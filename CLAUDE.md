# Impeccabyte marketing site

Drupal 11 marketing site for Impeccabyte, an Austin, Texas merchant services agency and authorized sales agent of Maverick Payments. Read `docs/BUILD_BRIEF.md` before any build work. The wireframes in `design/wireframes/` are the approved design.

## Stack and commands

- Drupal 11 on PHP 8.4 and MariaDB, in Docker. Drupal root in the container is `/opt/drupal`, web root `/opt/drupal/web`.
- Start: `docker compose up -d`. First install: `./scripts/setup.sh`.
- Drush: `./scripts/drush <command>`. Never install PHP or Composer on the host.
- Add a module: add it to `docker/Dockerfile`, then `docker compose up -d --build`, then `./scripts/drush en <module> -y`.
- Custom code lives only in `web/themes/custom/impeccabyte` and `web/modules/custom`. These are mounted into the container, so edits apply after `./scripts/drush cr`.
- After any change to site configuration: `./scripts/drush cex -y` and commit `config/sync`.

## How to work

- Build in the phases listed in `docs/BUILD_BRIEF.md`. Finish and check one phase before starting the next.
- Prefer configuration (content types, paragraphs, views, webforms) over custom PHP. Write a custom module only where the brief names one.
- Build UI as single directory components in `web/themes/custom/impeccabyte/components/`, one per design system component or paragraph type.
- Check your work in the running site: load the page with `curl -s localhost:8080/<path>` and look at `./scripts/drush watchdog:show` for errors. Do not report a phase done if the page errors.
- Commit at the end of each phase with a plain message. Never add co-author, generated-by or assistant attribution lines to commits or files.
- Never commit `.env`. Never print passwords from it.
- Ask before: deleting the database or volumes, changing the content model after content exists, adding a paid or external service.

## Design rules (from the Impeccabyte design system)

- Colors, type, spacing, radius and shadows come only from `css/tokens.css`. No raw hex values or off-scale sizes in component CSS.
- Clay `--clay-500` is the brand colour. The primary button uses `--clay-600` (hover `--clay-700`, press `--clay-800`) so its white label meets 4.5:1. Amber `--amber-400` is an accent only, always with ink text, never the main call to action. Clove `--clove-800` is for dark surfaces. Page background is `--paper`, never pure white. No pure black, no neutral gray.
- Headings h1 to h3 use Newsreader at weight 600. One italic clay word in a hero headline is the signature. Everything else uses Hanken Grotesk.
- Type sizes: 62 or 48 for hero headlines, 38 or 30 for section headlines, 18 for lead text, 16 for body, 14 for supporting text, 12 uppercase for eyebrows.
- All buttons and badges are full pills. Cards use a 20px radius, 1px `--ink-200` border and `--shadow-sm`. Inputs use 14px.
- Layout: 1240px max container, 24px gutter, 112px section padding (64px under 760px).
- Icons: Lucide, outline only, 1.75 stroke. No emoji or unicode symbols as icons.
- Motion: 140ms for color, 220ms for movement, soft ease-out, no bounce. Respect reduced motion.

## Voice

Warm, plain-spoken, encouraging, professional. Speak to "you", refer to the company as "we". Sentence case everywhere, including buttons and navigation. No emoji. No em dashes. Buttons start with a verb ("Get a quote", "See pricing"). State prices and fees plainly. Explain payments terms in plain words.

## Accessibility (WCAG 2.2 AA)

Follow the "Accessibility rules" section of the build brief. The short version: 4.5:1 text contrast, 3:1 for control outlines and focus rings, 24px minimum targets (44px for primary touch controls), real headings in order, every input labelled, nothing conveyed by color alone, keyboard support for every control.

## Content rules

- Keep every `[bracketed]` placeholder as a placeholder. Do not invent phone numbers, addresses, rates, names or statistics.
- Testimonials and case studies are placeholders until real, approved ones are supplied. Never present invented quotes as real.
- The footer disclosure must appear on every page: Impeccabyte is an authorized sales agent of Maverick Payments; banking products come from partner financial institutions; Impeccabyte is not a bank.
- Hardware prices and funding cutoff times in the wireframes come from Impeccabyte's internal pricing and funding references. Treat them as seed content the client can edit, not constants in code.
