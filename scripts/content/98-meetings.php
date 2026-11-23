<?php

/**
 * @file
 * Meetings by appointment (2026-11-23).
 *
 * Specialists work mobile, with no office: they meet merchants by
 * appointment, in person at the merchant's business or on Microsoft Teams.
 * The About us contact section and the "How do I reach you?" answer say so.
 *
 * Wording is only replaced while it still holds the old text, so edits made
 * in the admin are kept. On a fresh install scripts/content/89-about.php
 * already has the new wording and this script changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-meetings.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$swaps = [
  'Email us, and a local specialist in Austin is nearby and ready to help. When something goes wrong at the register, you reach someone who knows your setup.' => 'Email us to book a time with a local specialist in Austin, in person at your business or on Microsoft Teams. When something goes wrong at the register, you reach someone who knows your setup.',
  'A local specialist in Austin replies within one business day. Our mailing address' => 'A local specialist in Austin replies within one business day, and we meet by appointment, in person at your business or on Microsoft Teams. Our mailing address',
];

$updated = 0;
$text_types = ['string', 'string_long', 'text', 'text_long'];
foreach (\Drupal::entityTypeManager()->getStorage('paragraph')->loadByProperties(['type' => ['media_split', 'faq_item']]) as $paragraph) {
  $changed = FALSE;
  foreach ($paragraph->getFields() as $name => $field) {
    if (!str_starts_with($name, 'field_') || !in_array($field->getFieldDefinition()->getType(), $text_types, TRUE)) {
      continue;
    }
    $values = $field->getValue();
    foreach ($values as $delta => $item) {
      $new = str_replace(array_keys($swaps), array_values($swaps), $item['value'] ?? '');
      if ($new !== ($item['value'] ?? '')) {
        $values[$delta]['value'] = $new;
        $changed = TRUE;
      }
    }
    if ($changed) {
      $paragraph->set($name, $values);
    }
  }
  if ($changed) {
    $paragraph->save();
    $updated++;
  }
}

print "Meetings by appointment: $updated section(s) updated.\n";
