<?php

/**
 * @file
 * Even lengths for the three program descriptions on Pricing (2026-11-23).
 *
 * The Cash Price and Pro descriptions ran to three lines beside Simple's two,
 * so the "Starting at" prices did not line up across the cards. Both are
 * shortened to about Simple's length, saying the same thing.
 *
 * A description is only replaced while it still holds the previous wording,
 * so anything edited in the admin is kept. On a fresh install
 * scripts/content/20-pages.php already has the new wording and this script
 * changes nothing. Runs after scripts/content/96-pricing-programs.php.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-pricing-card-copy.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Old description => new description.
$bodies = [
  'Dual pricing: show a cash price and a card price. Card customers cover the processing cost.' => 'Dual pricing: a cash price and a card price. Card customers cover processing.',
  "Interchange plus: you pay the card network's actual cost, plus one small markup we show in writing." => "Interchange plus: the card network's actual cost plus one markup, in writing.",
];

$updated = 0;
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
foreach ($bodies as $old => $new) {
  foreach ($storage->loadByProperties(['type' => 'pricing_card', 'field_body' => $old]) as $card) {
    $card->set('field_body', $new)->save();
    $updated++;
  }
}

print "Pricing card descriptions: $updated updated.\n";
