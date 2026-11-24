<?php

/**
 * @file
 * The Hotel Perks closing band stresses "hotel", not "mind" (2026-11-24).
 *
 * With "mind" in italic just before the question mark, the italic letter
 * crowded the "?" and the emphasis fell on a filler word. Only a band still
 * set to "mind" is changed. Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-travel-cta-accent.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$updated = 0;
foreach (\Drupal::entityTypeManager()->getStorage('paragraph')->loadByProperties(['type' => 'cta_band', 'field_heading' => 'Have a hotel in mind?', 'field_accent_word' => 'mind']) as $band) {
  $band->set('field_accent_word', 'hotel')->save();
  $updated++;
}

print "Travel closing band: $updated updated.\n";
