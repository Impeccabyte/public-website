<?php

/**
 * @file
 * Puts the statement review first on Home (2026-11-23).
 *
 * A reviewed statement is the site's main goal (PRODUCT.md), but Home only
 * offered it in the closing band. Now:
 *
 * - Hero: "Upload a statement" (to the statement review on Pricing) is the
 *   main button, with "Get a quote" beside it. "See pricing" goes; Pricing
 *   stays in the main menu.
 * - "Lower your processing costs" card: links to the statement review, since
 *   its body promises the math on your real statement.
 * - FAQ intro: the link that opens the quote form says so instead of "Ask a
 *   local specialist".
 *
 * A value is only replaced while it still holds the old wording, so anything
 * edited in the admin is kept. On a fresh install 20-pages.php already has
 * the new wording and this script changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-home-statement-first.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$home = ib_node_by_alias('/home');
if (!$home) {
  print "No Home page yet; run scripts/content/20-pages.php first.\n";
  return;
}

$changed = 0;
$is_link = fn($item, string $uri, string $title) => $item && $item->uri === $uri && $item->title === $title;

foreach ($home->get('field_sections')->referencedEntities() as $section) {
  $save = FALSE;
  switch ($section->bundle()) {
    case 'hero':
      if ($is_link($section->get('field_link')->first(), 'internal:/get-a-quote', 'Get a quote')
        && $is_link($section->get('field_link_secondary')->first(), 'internal:/pricing', 'See pricing')) {
        $section->set('field_link', ib_link('/pricing#statement', 'Upload a statement'));
        $section->set('field_link_secondary', ib_link('/get-a-quote', 'Get a quote'));
        $save = TRUE;
      }
      break;

    case 'feature_grid':
      foreach ($section->get('field_items')->referencedEntities() as $card) {
        if ($card->get('field_title')->value === 'Lower your processing costs'
          && $is_link($card->get('field_link')->first(), 'internal:/pricing', 'Compare pricing programs')) {
          $card->set('field_link', ib_link('/pricing#statement', 'Get a free statement review'));
          $card->save();
          $changed++;
        }
      }
      break;

    case 'faq':
      $old = "<p>Don't see yours? <a href=\"/get-a-quote\">Ask a local specialist</a> and we'll reply within one business day.</p>";
      if ($section->hasField('field_intro_html') && $section->get('field_intro_html')->value === $old) {
        $section->get('field_intro_html')->value = "<p>Don't see yours? <a href=\"/get-a-quote\">Ask us with the quote form</a> and we'll reply within one business day.</p>";
        $save = TRUE;
      }
      break;
  }
  if ($save) {
    $section->save();
    $changed++;
  }
}

if ($changed) {
  \Drupal::service('cache_tags.invalidator')->invalidateTags(['rendered']);
}
print "Home statement first: $changed value(s) updated.\n";
