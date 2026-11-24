<?php

/**
 * @file
 * Social share images (2026-11-24).
 *
 * Each key page, industry page and specialty page has its own 1200x630
 * image in web/themes/custom/impeccabyte/images/og/, drawn from the OG
 * image canvas. Every other page shares the home page image.
 * impeccabyte_site_metatags_alter() picks the image by path and sets the
 * Open Graph and Twitter card tags, so this script only turns on the
 * Twitter card tags.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/89-og-images.php
 * then export config.
 */

\Drupal::service('module_installer')->install(['metatag_twitter_cards']);
