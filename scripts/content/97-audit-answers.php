<?php

/**
 * @file
 * Answers from the content audit walk-through on 2026-11-23.
 *
 * - Home stat: "25+" devices instead of "40+", to match the catalog.
 * - On the Go: tap to pay names the four ways we set it up (Authorize.Net,
 *   iPOSGo, SwipeSimple Tap to Pay, the Clover Go app), and the intro no
 *   longer promises a team "to call" (there is no phone number).
 * - Dual Pricing: the device grid shows four of the 13 ready devices and
 *   links to the catalog, instead of all of them.
 *
 * A value is only replaced while it still holds the old wording, so edits
 * made in the admin are kept. On a fresh install the source scripts already
 * have the new wording and this script changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-audit-answers.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$changes = [];
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');

// Old wording => new wording, inside any text field of a paragraph.
$swaps = [
  'All on one merchant account, with a local team to call.' => 'All on one merchant account, with a local team behind you.',
  'on most smartphones, iPhone and Android alike, with no extra hardware. For chip cards' => 'on most smartphones, iPhone and Android alike, with no extra hardware. We set it up with Authorize.Net, iPOSGo, SwipeSimple Tap to Pay or the Clover Go app. For chip cards',
];
$text_types = ['string', 'string_long', 'text', 'text_long', 'text_with_summary'];
foreach ($storage->loadMultiple() as $paragraph) {
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
    $changes[] = "wording updated in {$paragraph->bundle()} {$paragraph->id()}";
  }
}

// Home stat.
foreach ($storage->loadByProperties(['type' => 'stat', 'field_value' => '40+']) as $stat) {
  if ($stat->get('field_label')->value === 'Terminals, readers and POS systems we can set up') {
    $stat->set('field_value', '25+')->save();
    $changes[] = 'Home stat: 25+ devices';
  }
}

// Dual Pricing device grid: four devices and a link to the rest.
foreach ($storage->loadByProperties(['type' => 'card_grid', 'field_source' => 'dual_pricing', 'field_heading' => 'Devices that are dual pricing ready']) as $grid) {
  $devices = [];
  foreach (['/hardware/dejavoo-z8', '/hardware/dejavoo-qd4', '/hardware/valor-vp550', '/hardware/dejavoo-p3'] as $alias) {
    if ($device = ib_node_by_alias($alias)) {
      $devices[] = ['target_id' => $device->id()];
    }
  }
  $grid->set('field_source', 'manual')->set('field_devices', $devices);
  if ($grid->get('field_intro')->value === 'These show both prices on screen and print them on the receipt. Already have one? We can usually reprogram it.') {
    $grid->set('field_intro', 'A few of the 13 devices that show both prices on screen and print them on the receipt. Already have one? We can usually reprogram it.');
  }
  $grid->save();
  $changes[] = 'Dual Pricing grid: four devices';
}

print $changes ? "Audit answers:\n- " . implode("\n- ", $changes) . "\n" : "Audit answers: nothing to change.\n";
