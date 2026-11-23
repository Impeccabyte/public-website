<?php

/**
 * @file
 * Finishes the placeholders found in a sweep of every page on 2026-11-23.
 *
 * - Example customer stories come off until real, approved ones are
 *   supplied: any testimonial or case study section still flagged as a
 *   placeholder (the Home testimonial and the Restaurants & Bars story) is
 *   removed from its page and deleted. A real one, without the flag, is never
 *   touched.
 * - Clover Mini's receipt printer: "Built-in thermal receipt printer", from
 *   clover.com/mini as checked on 2026-11-23.
 *
 * The Get a quote sidebar's example quote was in the theme
 * (impeccabyte_preprocess_page) and is gone from there.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-finish-placeholders.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$changes = [];
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');

// Example customer stories.
$ids = \Drupal::entityQuery('paragraph')
  ->accessCheck(FALSE)
  ->condition('type', ['testimonial', 'case_study'], 'IN')
  ->condition('field_placeholder', 1)
  ->execute();
foreach ($storage->loadMultiple($ids) as $story) {
  $host = $story->getParentEntity();
  if ($host && $host->hasField('field_sections')) {
    $kept = array_values(array_filter($host->get('field_sections')->getValue(), fn($item) => (int) $item['target_id'] !== (int) $story->id()));
    $host->set('field_sections', $kept)->save();
    $where = $host->getEntityTypeId() === 'node' ? \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $host->id()) : $host->getEntityTypeId();
    $changes[] = "example {$story->bundle()} removed from $where";
  }
  $story->delete();
}

// Clover Mini's receipt printer.
foreach ($storage->loadByProperties(['type' => 'spec_row', 'field_spec_value' => '[Confirm with current Clover spec sheet]']) as $row) {
  $row->set('field_spec_value', 'Built-in thermal receipt printer')->save();
  $changes[] = 'Clover Mini receipt printer filled in';
}

print $changes ? "Placeholders:\n- " . implode("\n- ", $changes) . "\n" : "Placeholders: nothing to change.\n";
