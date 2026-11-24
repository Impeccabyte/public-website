<?php

/**
 * @file
 * Leads the Home hero with clarity instead of "local" (2026-11-23).
 *
 * PRODUCT.md: Austin is where we are, not the pitch. The hero now makes the
 * founding promise (every charge explained in plain English, every fee in
 * writing) and shows an example statement with three charges explained,
 * instead of the dashboard and terminal. Austin stays in the eyebrow and the
 * search title. The industry picker's heading also leads with fit instead of
 * Austin.
 *
 * Each value is only replaced while it still holds the old wording, so
 * anything edited in the admin is kept. On a fresh install 20-pages.php
 * already has the new wording and this script changes nothing.
 *
 * Needs the "Plain-English statement" illustration choice from
 * scripts/setup/50-solutions-industries.php.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-home-hero-clarity.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$home = ib_node_by_alias('/home');
if (!$home) {
  print "No Home page yet; run scripts/content/20-pages.php first.\n";
  return;
}

$changed = 0;
foreach ($home->get('field_sections')->referencedEntities() as $hero) {
  if ($hero->bundle() !== 'hero') {
    continue;
  }
  $swap = function (string $field, $old, $new) use ($hero, &$changed) {
    $current = $field === 'field_checklist'
      ? array_column($hero->get($field)->getValue(), 'value')
      : $hero->get($field)->value;
    if ($current === $old) {
      $hero->set($field, $new);
      $changed++;
    }
  };
  $swap('field_heading', 'Getting paid should feel impeccably easy.', 'Know every fee before you sign.');
  $swap('field_accent_word', 'impeccably', 'every');
  $swap('field_intro',
    "We're a local Austin team that sets up card processing, POS hardware and online payments for small businesses and first-time founders. Clear pricing, real people, and gear that just works.",
    "Send us your processing statement and we'll explain every charge in plain English, then set you up with the pricing and hardware that fit your business. Starting out? We'll walk you through your first payment.");
  $swap('field_checklist', ['Free statement review', 'Every fee in writing', 'Support from Austin'], ['Free statement review', 'Every fee in writing', 'A setup for your industry']);
  if (empty($hero->get('field_hero_art')->value) && $hero->get('field_media')->isEmpty()) {
    $hero->set('field_hero_art', 'statement');
    $changed++;
  }
  if ($changed) {
    $hero->save();
  }
  break;
}

// The industry picker's heading leads with fit, not Austin (2026-11-23).
foreach ($home->get('field_sections')->referencedEntities() as $section) {
  if ($section->bundle() === 'chip_links' && $section->get('field_heading')->value === 'Built for the way Austin does business') {
    $section->get('field_heading')->value = 'Pick your industry, see the setup that fits';
    $section->save();
    $changed++;
  }
}

if ($changed) {
  \Drupal::service('cache_tags.invalidator')->invalidateTags(['rendered']);
}
print "Home hero clarity: $changed value(s) updated.\n";
