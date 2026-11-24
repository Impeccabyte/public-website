<?php

/**
 * @file
 * Matches each pricing program to hardware that supports it (2026-11-23).
 *
 * Dual pricing (the Cash Price program) is only for Dejavoo and Valor
 * terminals. Clover offers a cash discount instead: the posted price is the
 * card price, and customers who pay cash get a discount at checkout
 * (docs/maverick-offerings.md, "Programs on Clover"). Ry chose to keep the
 * Clover setups and describe Clover's cash discount, so:
 *
 * - Home, Restaurants & Bars and Food Trucks panels: the Cash Price line
 *   becomes a cash discount line, and the dual pricing sentence goes.
 * - Home, Auto Repair panel: Valor VP550 keeps Cash Price, but the note no
 *   longer calls the card price a fee.
 * - Food Trucks page: the pain-point card, the FAQ and the meta description
 *   describe the cash discount, and the sample setup lists it as an add-on
 *   instead of linking the Dual pricing page.
 *
 * The pricing playbook has no cash discount price, so none is shown.
 *
 * A value is only replaced while it still holds the old wording, so anything
 * edited in the admin is kept. On a fresh install the source scripts
 * (20-pages.php, 67-food-trucks.php) already have the new wording and this
 * script changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-clover-cash-discount.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
$changed = 0;

// Paragraph type => field => [old value => new value]. Old values are exact.
$replace = [
  'chip_item' => [
    'field_title' => [
      'Faster tables, cleaner tips, smaller fees' => 'Faster tables, cleaner tips, lower card costs',
    ],
    'field_body' => [
      'Pay at the table, split checks in seconds and adjust tips before close. Dual pricing keeps card costs off your margins.' => 'Pay at the table, split checks in seconds and adjust tips before close. A cash discount on Clover helps offset what you pay to process cards.',
    ],
  ],
  'setup_line' => [
    'field_note' => [
      'Card customers cover the processing cost' => 'A lower price for customers who pay cash',
      'Offset fees on large repairs' => 'Card customers cover processing on big tickets',
    ],
  ],
  'feature_card' => [
    'field_body' => [
      'Dual pricing lets card customers cover processing, with a clear sign at the window.' => 'A cash discount on your Clover gives customers who pay cash a lower price and helps offset what you pay to process cards.',
    ],
  ],
  'faq_item' => [
    'field_question' => [
      'Does dual pricing work at a truck window?' => 'Can I offer a cash discount at the window?',
    ],
    'field_answer' => [
      '<p>Yes. A sign at the window explains the two prices, and the handheld shows both totals before the customer pays. Cash stays the lower price.</p>' => "<p>Yes. With Clover's cash discount, your posted prices are your card prices, and customers who pay cash get a discount at checkout. We set it up with you and explain how it works before your first shift.</p>",
    ],
  ],
];

// The Restaurants & Bars and Food Trucks lines pair Cash Price with Clover.
// Rename them first, while their old notes still identify them.
$rename = ['Card customers cover the processing cost', 'Keep more of every taco'];
$ids = $storage->getQuery()->accessCheck(FALSE)
  ->condition('type', 'setup_line')
  ->condition('field_title.value', 'Cash Price')
  ->condition('field_note.value', $rename, 'IN')
  ->execute();
foreach ($storage->loadMultiple($ids) as $line) {
  if ($line->get('field_title')->value === 'Cash Price' && in_array($line->get('field_note')->value, $rename, TRUE)) {
    $line->get('field_title')->value = 'Cash discount';
    $line->save();
    $changed++;
  }
}

foreach ($replace as $type => $fields) {
  foreach ($fields as $field => $pairs) {
    foreach ($pairs as $old => $new) {
      $ids = $storage->getQuery()->accessCheck(FALSE)->condition('type', $type)->condition($field . '.value', $old)->execute();
      foreach ($storage->loadMultiple($ids) as $paragraph) {
        if ($paragraph->get($field)->value !== $old) {
          continue;
        }
        $paragraph->get($field)->value = $new;
        $paragraph->save();
        $changed++;
      }
    }
  }
}

// Food Trucks page: the sample setup drops the Dual pricing line and lists
// a cash discount as an add-on; the meta description follows.
$page = ib_node_by_alias('/industries/food-trucks');
$dual = ib_node_by_alias('/solutions/dual-pricing');
if ($page && $dual) {
  foreach ($page->get('field_sections')->referencedEntities() as $section) {
    if ($section->bundle() !== 'bundle') {
      continue;
    }
    $keep = [];
    $drop = [];
    foreach ($section->get('field_items')->referencedEntities() as $item) {
      $is_dual = $item->get('field_label')->value === 'Pricing'
        && (int) $item->get('field_reference')->target_id === (int) $dual->id()
        && $item->get('field_note')->value === 'Card customers cover processing on each order';
      if ($is_dual) {
        $drop[] = $item;
      }
      else {
        $keep[] = ['target_id' => $item->id(), 'target_revision_id' => $item->getRevisionId()];
      }
    }
    if ($drop) {
      $section->set('field_items', $keep);
      $addons = array_column($section->get('field_addons')->getValue(), 'value');
      if (!in_array('cash discount', $addons, TRUE)) {
        $addons[] = 'cash discount';
        $section->set('field_addons', $addons);
      }
      $section->save();
      foreach ($drop as $item) {
        $item->delete();
      }
      $changed++;
    }
  }
  $old_meta = 'Card payments for Austin food trucks: cellular handhelds, offline mode, dual pricing and deposits set up before your first shift.';
  if ($page->hasField('field_meta_description') && $page->get('field_meta_description')->value === $old_meta) {
    $page->set('field_meta_description', 'Card payments for Austin food trucks: cellular handhelds, offline mode, a cash discount on Clover and deposits set up before your first shift.');
    $page->save();
    $changed++;
  }
}

if ($changed) {
  \Drupal::service('cache_tags.invalidator')->invalidateTags(['rendered']);
}
echo "Clover cash discount: $changed value(s) updated.\n";
