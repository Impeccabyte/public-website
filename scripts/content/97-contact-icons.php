<?php

/**
 * @file
 * Icons in place of the "Email" and "Mailing address" titles in the About us
 * "Reach us" list (2026-11-23).
 *
 * A checklist line that starts with "mail: " or "mailbox: " shows that icon
 * instead of a check, and the label is read out to screen readers only (see
 * the media-split component). This turns the two titled lines into icon
 * lines. A line is only changed while it still holds the old wording, so
 * anything edited in the admin is kept. On a fresh install
 * scripts/content/89-about.php already has the icon lines and this script
 * changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-contact-icons.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$lines = [
  'Email: howdy@impeccabyte.com' => 'mail: howdy@impeccabyte.com',
  'Mailing address, mail only: 17350 State Hwy 249, Ste 220 #37005, Houston, TX 77064' => 'mailbox: 17350 State Hwy 249, Ste 220 #37005, Houston, TX 77064',
];

$updated = 0;
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
foreach ($storage->loadByProperties(['type' => 'media_split', 'field_heading' => 'A real person, close by']) as $section) {
  $items = $section->get('field_checklist')->getValue();
  $changed = FALSE;
  foreach ($items as $delta => $item) {
    if (isset($lines[$item['value']])) {
      $items[$delta]['value'] = $lines[$item['value']];
      $changed = TRUE;
    }
  }
  if ($changed) {
    $section->set('field_checklist', $items)->save();
    $updated++;
  }
}

print "Contact icons on About us: $updated section(s) updated.\n";
