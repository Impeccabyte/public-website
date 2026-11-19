Build the Impeccabyte marketing site in this Drupal 11 project.

1. Read `CLAUDE.md`, `docs/BUILD_BRIEF.md` and `design/README.md` in full.
2. Check the environment: run `docker compose ps` and `./scripts/drush status`. If Drupal is not installed, run `./scripts/setup.sh` and fix anything that fails before going further. If a module in `docker/Dockerfile` will not install, tell me which one and why, and propose a replacement before changing the plan.
3. Work through the phases in `docs/BUILD_BRIEF.md` in order. At the start of each phase, tell me in a few lines what you will build. At the end, run that phase's checks, export config, commit, and give me a short summary with anything you could not finish.
4. Translate the wireframe markup in `design/wireframes/` into Twig components. Match layout, copy, spacing and states. Use the tokens in the theme, not the inline values, where they differ.
5. Stop and ask me when the brief and the wireframes disagree, when a choice would change the content model, or when you need real content that is still a `[bracketed]` placeholder.

Do not skip the accessibility rules. Do not invent content. Start with phase 1.
