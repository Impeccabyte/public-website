<?php

/**
 * @file
 * Phase 5, solutions and industries: the fields on both content types and
 * the extra fields their sections need.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/50-solutions-industries.php
 * then export config.
 */

require_once __DIR__ . '/_helpers.php';

// Solution.
ib_field('node', 'solution', 'field_category', 'entity_reference', 'Category', ['storage' => ['target_type' => 'taxonomy_term'], 'settings' => ib_ref_terms('solution_category'), 'required' => TRUE, 'widget' => 'options_select', 'weight' => 0, 'hide' => TRUE]);
ib_field('node', 'solution', 'field_short_description', 'string', 'Short description', ['weight' => 1, 'description' => 'One line for menus and cards.', 'hide' => TRUE]);
ib_field('node', 'solution', 'field_icon', 'string', 'Icon', ['weight' => 2, 'description' => 'A Lucide icon name, such as store or globe.', 'hide' => TRUE]);

// Industry page.
ib_field('node', 'industry_page', 'field_industry', 'entity_reference', 'Industry', ['storage' => ['target_type' => 'taxonomy_term'], 'settings' => ib_ref_terms('industry'), 'required' => TRUE, 'widget' => 'options_select', 'weight' => 0, 'hide' => TRUE]);

// The hero can show a drawn illustration when there is no photo.
ib_field('paragraph', 'hero', 'field_hero_art', 'list_string', 'Illustration', [
  'storage' => ['allowed_values' => $hero_art = ['dashboard' => 'Dashboard and terminal', 'receipt' => 'Dual pricing receipt', 'statement' => 'Plain-English statement', 'confirmation' => 'Hotel confirmation']],
  'widget' => 'options_select',
  'weight' => 7,
  'description' => 'Used by the split variant when no media is chosen.',
]);
// ib_field() leaves existing storage alone, so a site created before a
// choice was added (the statement and the hotel confirmation, 2026-11-23)
// gets the missing ones here.
$art_storage = \Drupal\field\Entity\FieldStorageConfig::loadByName('paragraph', 'field_hero_art');
$allowed = $art_storage->getSetting('allowed_values');
if ($missing = array_diff_key($hero_art, $allowed)) {
  $art_storage->setSetting('allowed_values', $allowed + $missing)->save();
}
ib_field('paragraph', 'hero', 'field_media_label', 'string', 'Photo description', ['weight' => 8, 'description' => 'Read by screen readers when a photo placeholder is shown.']);

// Setup items that are not devices carry their own buy price.
ib_field('paragraph', 'bundle_item', 'field_buy_price', 'decimal', 'Buy price each', ['storage' => ['precision' => 10, 'scale' => 2], 'settings' => ['prefix' => '$'], 'weight' => 6, 'description' => 'Overrides the device buy price. Used for items that are not devices.']);
ib_field('paragraph', 'bundle', 'field_anchor', 'string', 'Anchor', ['weight' => 7, 'description' => 'Lets links jump to this section, for example setup.']);
ib_field('paragraph', 'savings_estimator', 'field_anchor', 'string', 'Anchor', ['weight' => 11, 'description' => 'Lets links jump to this section, for example savings.']);

print "Solutions and industries are in place.\n";
