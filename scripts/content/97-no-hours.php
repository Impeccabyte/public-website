<?php

/**
 * @file
 * No business hours on About us (2026-11-23).
 *
 * The "Reach us" checklist loses its "Hours: [Business hours]" line. Hours
 * only showed next to a phone number elsewhere, and there is no phone
 * number, so the site lists no hours at all.
 *
 * Only the unedited placeholder line is removed, so hours entered in the
 * admin are kept. On a fresh install scripts/content/89-about.php already
 * leaves the line out and this script changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-no-hours.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$updated = 0;
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
foreach ($storage->loadByProperties(['type' => 'media_split', 'field_heading' => 'A real person, close by']) as $section) {
  $items = $section->get('field_checklist')->getValue();
  $kept = array_values(array_filter($items, fn($item) => ($item['value'] ?? '') !== 'Hours: [Business hours]'));
  if (count($kept) !== count($items)) {
    $section->set('field_checklist', $kept)->save();
    $updated++;
  }
}

print "Business hours on About us: removed from $updated section(s).\n";
