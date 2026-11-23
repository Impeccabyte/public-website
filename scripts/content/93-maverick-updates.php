<?php

/**
 * @file
 * Brings the original non-Clover devices in line with Maverick's knowledge
 * base (kb.maverickpayments.com, read 2026-11-19), and corrects two facts:
 *
 * - Valor VP550 is an Android mobile terminal (Wi-Fi and 4G), not a
 *   countertop terminal.
 * - PAX A920 Pro supports surcharging but not dual pricing through Maverick.
 *
 * Spec tables and industries are added to Dejavoo Z8, Dejavoo QD4, PAX A920
 * Pro and Valor VP550. Corrections only replace a value that still holds the
 * old wording, so anything edited in the admin is kept. On a fresh install
 * the source scripts already have the corrected values and this script only
 * adds the spec tables. Terminal costs from the knowledge base are not used.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/93-maverick-updates.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$term_id = fn(string $vid, string $name) => ib_term_id($vid, $name);
$spec = fn(string $label, string $value) => ib_paragraph('spec_row', ['label' => $label, 'spec_value' => $value]);
$changes = [];

$brands = 'Visa, Mastercard, Discover and American Express';
$entry = 'Chip (with PIN), swipe and tap, including Apple Pay and Google Pay';
$pin = 'PIN debit and EBT (cash and food)';
$dual = 'Dual pricing, surcharging and cash discount';
$wifi4g5 = 'Wi-Fi (2.4 and 5 GHz) and 4G with an optional data plan';
$base = fn(string $type, string $connectivity) => [['Terminal type', $type], ['Connectivity', $connectivity], ['Cards accepted', $brands], ['How customers pay', $entry], ['PIN payments', $pin]];
$counter = ['Retail', 'Restaurants & Bars', 'Auto Repair'];
$mobile = ['Retail', 'Restaurants & Bars', 'Auto Repair', 'Food Trucks', 'Pop-Ups & Markets'];

// Alias => spec rows, industries to add.
$updates = [
  'dejavoo-z8' => [array_merge($base('Countertop', 'Ethernet and Wi-Fi (2.4 GHz)'), [['Gift cards', 'Factor4, SAGE, Paya, STS Gift and more'], ['Pricing programs', $dual]]), $counter],
  'dejavoo-qd4' => [array_merge($base('Countertop', 'Ethernet and Wi-Fi (2.4 GHz)'), [['Screen', '5.5-inch touchscreen'], ['Receipt printer', 'Built in'], ['Gift cards', 'Factor4, SAGE, Paya, STS Gift and more'], ['Pricing programs', $dual]]), $counter],
  'pax-a920-pro' => [array_merge($base('Wireless handheld', $wifi4g5), [['Gift cards', 'Factor4 and more'], ['Pricing programs', 'Surcharging']]), $mobile],
  'valor-vp550' => [array_merge($base('Wireless handheld', $wifi4g5), [['Gift cards', 'Factor4 and Valuetec'], ['Pricing programs', $dual]]), $mobile],
];

foreach ($updates as $slug => [$rows, $industries]) {
  $node = ib_node_by_alias('/hardware/' . $slug);
  if (!$node) {
    continue;
  }
  $changed = FALSE;
  if ($node->get('field_specs')->isEmpty()) {
    $node->set('field_specs', array_map(fn(array $row) => $spec($row[0], $row[1]), $rows));
    $changed = TRUE;
  }
  $current = array_column($node->get('field_industries')->getValue(), 'target_id');
  foreach ($industries as $name) {
    $tid = $term_id('industry', $name);
    if ($tid && !in_array($tid, $current)) {
      $current[] = $tid;
      $changed = TRUE;
    }
  }
  $node->set('field_industries', array_map(fn($tid) => ['target_id' => $tid], $current));
  if ($changed) {
    $node->save();
    $changes[] = "$slug: specs and industries";
  }
}

// Replace a field value only while it still holds the old wording.
$swap = function ($entity, string $field, $old, $new) {
  $value = $entity->get($field)->getValue();
  $flat = array_map(fn($item) => $item['value'] ?? $item['target_id'] ?? NULL, $value);
  $old = (array) $old;
  if ($flat == $old) {
    $entity->set($field, $new);
    return TRUE;
  }
  return FALSE;
};

// Valor VP550: a mobile terminal, not a countertop.
if ($vp550 = ib_node_by_alias('/hardware/valor-vp550')) {
  $changed = FALSE;
  $changed |= $swap($vp550, 'field_device_type', [$term_id('device_type', 'Countertop Terminal')], ['target_id' => $term_id('device_type', 'Handheld')]);
  $changed |= $swap($vp550, 'field_sales_channels', [$term_id('sales_channel', 'In store')], [['target_id' => $term_id('sales_channel', 'Tableside')], ['target_id' => $term_id('sales_channel', 'On the go')]]);
  $changed |= $swap($vp550, 'field_card_summary', 'Value Android countertop built for dual pricing.', 'Android mobile terminal built for dual pricing.');
  $changed |= $swap($vp550, 'field_summary',
    'A value Android countertop built for dual pricing, so card customers cover the processing cost. A good fit for higher-ticket counters like auto repair.',
    'An Android mobile terminal built for dual pricing, so card customers cover the processing cost. It runs on Wi-Fi or 4G, so it works at the counter, in the bay or out on a service call.');
  $wallets = 'Tap, chip and swipe, plus Apple Pay and Google Pay';
  $reprogram = 'Already own one? Many can be reprogrammed for your new account';
  $programmed = 'Ships programmed for your account';
  $changed |= $swap($vp550, 'field_key_features',
    ['Built for dual pricing', 'Android countertop', $wallets, $reprogram, $programmed],
    ['Built for dual pricing', 'Android mobile terminal', 'Wi-Fi or 4G (data plan optional)', $wallets, $reprogram, $programmed]);
  $old_related = array_map(fn($s) => ib_node_by_alias('/hardware/' . $s)?->id(), ['dejavoo-z8', 'dejavoo-qd4', 'clover-compact']);
  $new_related = array_values(array_filter(array_map(fn($s) => ib_node_by_alias('/hardware/' . $s)?->id(), ['valor-vl550', 'dejavoo-qd2', 'pax-a920-pro'])));
  $changed |= $swap($vp550, 'field_related_devices', $old_related, array_map(fn($id) => ['target_id' => $id], $new_related));
  if ($changed) {
    $vp550->save();
    $changes[] = 'valor-vp550: now a handheld mobile terminal';
  }
}

// PAX A920 Pro: surcharging, not dual pricing.
if ($pax = ib_node_by_alias('/hardware/pax-a920-pro')) {
  $changed = FALSE;
  if ($pax->get('field_dual_pricing_ready')->value) {
    $pax->set('field_dual_pricing_ready', 0);
    $changed = TRUE;
  }
  $features = array_column($pax->get('field_key_features')->getValue(), 'value');
  if (($i = array_search('Dual pricing ready', $features)) !== FALSE) {
    $features[$i] = 'Surcharging supported';
    $pax->set('field_key_features', $features);
    $changed = TRUE;
  }
  if ($changed) {
    $pax->save();
    $changes[] = 'pax-a920-pro: dual pricing removed';
  }
}

// Dejavoo QD4: screen size and printer from the knowledge base.
if ($qd4 = ib_node_by_alias('/hardware/dejavoo-qd4')) {
  $features = array_column($qd4->get('field_key_features')->getValue(), 'value');
  if (($i = array_search('Large touchscreen', $features)) !== FALSE) {
    array_splice($features, $i, 1, ['5.5-inch touchscreen', 'Built-in receipt printer']);
    $qd4->set('field_key_features', $features)->save();
    $changes[] = 'dejavoo-qd4: screen and printer features';
  }
}

// Page copy that called the VP550 a countertop.
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
$copy = [
  ['setup_line', 'field_title', 'Valor VP550 countertop', 'Valor VP550 terminal'],
  ['feature_card', 'field_note', 'Clover Compact · Dejavoo Z8 · Valor VP550', 'Clover Compact · Dejavoo Z8 · Valor VP100'],
  ['bundle_item', 'field_note', 'Countertop terminal built for dual pricing', 'Android terminal built for dual pricing, on Wi-Fi or 4G'],
];
foreach ($copy as [$type, $field, $old, $new]) {
  foreach ($storage->loadByProperties(['type' => $type, $field => $old]) as $paragraph) {
    $paragraph->set($field, $new);
    if ($type === 'bundle_item' && $paragraph->get('field_label')->value === 'Front counter') {
      $paragraph->set('field_label', 'At the counter or in the bay');
    }
    $paragraph->save();
    $changes[] = "$type copy: $new";
  }
}

if ($changes) {
  // Paragraph edits show on their pages once the render cache is cleared.
  \Drupal::service('cache_tags.invalidator')->invalidateTags(['node_list', 'rendered']);
}
print $changes ? "Maverick updates:\n- " . implode("\n- ", $changes) . "\n" : "Maverick updates: nothing to change.\n";
