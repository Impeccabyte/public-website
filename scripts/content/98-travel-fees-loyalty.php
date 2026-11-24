<?php

/**
 * @file
 * Travel: no planning fee, and loyalty points (Ry, 2026-11-24).
 *
 * The travel pages had a "[Planning fee]" placeholder and an unanswered
 * loyalty points question. There is no planning fee, and clients can use the
 * loyalty points from their favorite hotel and travel programs. The pages say
 * "no planning fee", never "no fees", which would cover costs we don't
 * control.
 *
 * Wording is only replaced while it still holds the old text, so edits made
 * in the admin are kept. On a fresh install scripts/content/52-travel.php
 * already has the new wording and this script changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-travel-fees-loyalty.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$swaps = [
  'Any planning fee agreed upfront' => 'No planning fee',
  'Hotel options with each one\'s perks spelled out, and any planning fee agreed with you before we start.' => 'Hotel options with each one\'s perks spelled out, so you can choose before we book.',
  'For bigger trips with flights, restaurants or several stops, our planning fee is [Planning fee], agreed with you upfront.' => 'For bigger trips with flights, restaurants or several stops, we plan it all with you, with no planning fee.',
  'In most cases the hotel rate is the same as booking direct. If your trip needs planning, such as flights or several stops, we agree any planning fee with you before we start.' => 'In most cases the hotel rate is the same as booking direct, and we don\'t charge a planning fee, even for trips with flights or several stops.',
  'Do I keep my hotel loyalty points?' => 'Can I use my loyalty points?',
  '[Answer to confirm with Fora Travel.]' => 'Yes. You can use the loyalty points from your favorite hotel and travel programs on the trips we plan with you.',
  'In most cases the rate is the same as booking direct. Any planning fee, [Planning fee], is agreed upfront.' => 'In most cases the rate is the same as booking direct, and there\'s no planning fee.',
  'Loyalty points: [answer to confirm with Fora Travel].' => 'You can use the loyalty points from your favorite hotel and travel programs.',
];

$updated = 0;
$text_types = ['string', 'string_long', 'text', 'text_long'];
foreach (\Drupal::entityTypeManager()->getStorage('paragraph')->loadByProperties(['type' => ['hero', 'step', 'feature_card', 'faq_item', 'callout']]) as $paragraph) {
  $changed = FALSE;
  foreach ($paragraph->getFields() as $name => $field) {
    if (!str_starts_with($name, 'field_') || !in_array($field->getFieldDefinition()->getType(), $text_types, TRUE)) {
      continue;
    }
    $values = $field->getValue();
    $field_changed = FALSE;
    foreach ($values as $delta => $item) {
      $new = str_replace(array_keys($swaps), array_values($swaps), $item['value'] ?? '');
      if ($new !== ($item['value'] ?? '')) {
        $values[$delta]['value'] = $new;
        $field_changed = TRUE;
      }
    }
    if ($field_changed) {
      $paragraph->set($name, $values);
      $changed = TRUE;
    }
  }
  if ($changed) {
    $paragraph->save();
    $updated++;
  }
}

print "Travel fees and loyalty: $updated section(s) updated.\n";
