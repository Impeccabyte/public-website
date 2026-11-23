<?php

/**
 * @file
 * The 12 devices from the hardware catalog wireframe, with Clover Mini's
 * full page content from the device wireframe.
 *
 * Prices are the wireframe's seed values only. They are stored as numbers
 * and stay hidden while "Show prices" is off. Nothing here comes from the
 * internal pricing sheets.
 *
 * Safe to re-run: a device that already exists at its address is left alone.
 * Run it with
 *   ./scripts/drush php:script scripts/content/30-devices.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$term = fn(string $vid, string $name) => ['target_id' => ib_term_id($vid, $name)];
$terms = fn(string $vid, array $names) => array_map(fn($name) => $term($vid, $name), $names);

// Sales channel follows the device type. Industries are the ones the Home
// wireframe pairs each device with.
$channels = [
  'Countertop Terminal' => ['In store'],
  'POS System' => ['In store'],
  'Self-Order Kiosk' => ['In store'],
  'Handheld' => ['Tableside', 'On the go'],
  'Mobile Reader' => ['On the go'],
];

// Name, alias, manufacturer, type, card line, lease, buy, order, industries,
// dual pricing ready, featured.
$devices = [
  ['Clover Mini', 'clover-mini', 'Clover', 'POS System', '8-inch all-in-one counter station with 500+ apps.', 60, 1189, 1, ['Retail'], FALSE, TRUE],
  ['Clover Flex', 'clover-flex', 'Clover', 'Handheld', 'Tableside and curbside pay with a built-in printer.', 50, 1029, 2, ['Restaurants & Bars', 'Food Trucks'], FALSE, TRUE],
  ['Clover Station Duo', 'clover-station-duo', 'Clover', 'POS System', 'Dual-screen flagship POS for busy counters.', 135, 2739, 3, ['Restaurants & Bars'], FALSE, FALSE],
  ['Clover Station Solo', 'clover-station-solo', 'Clover', 'POS System', 'Big-screen POS with printer for full service.', 125, 2469, 4, [], FALSE, FALSE],
  ['Clover Compact', 'clover-compact', 'Clover', 'Countertop Terminal', 'A small, simple terminal for tight counters.', 20, 399, 5, ['Salons & Spas'], FALSE, FALSE],
  ['Clover Go', 'clover-go', 'Clover', 'Mobile Reader', 'Pocket reader that pairs with your phone.', 10, 209, 6, ['Home Services', 'Nonprofits'], FALSE, TRUE],
  ['Clover Kiosk', 'clover-kiosk', 'Clover', 'Self-Order Kiosk', '24-inch self-order screen to shorten the line.', 215, 4259, 7, [], FALSE, FALSE],
  ['Dejavoo Z8', 'dejavoo-z8', 'Dejavoo', 'Countertop Terminal', 'Budget-friendly Wi-Fi terminal, dual pricing ready.', 20, 399, 1, ['Healthcare & Dental'], TRUE, TRUE],
  ['Dejavoo QD4', 'dejavoo-qd4', 'Dejavoo', 'Countertop Terminal', 'Android smart terminal with a large touchscreen.', 25, 499, 2, [], FALSE, FALSE],
  ['PAX A920 Pro', 'pax-a920-pro', 'PAX', 'Handheld', 'Flexible handheld for tableside and delivery.', 35, 749, 1, [], FALSE, FALSE],
  ['Valor VP550', 'valor-vp550', 'Valor', 'Handheld', 'Android mobile terminal built for dual pricing.', 30, 599, 1, ['Auto Repair'], TRUE, FALSE],
  ['SwipeSimple B350', 'swipesimple-b350', 'SwipeSimple', 'Mobile Reader', 'The lowest-cost reader for phones and tablets.', 10, 199, 1, ['Pop-Ups & Markets'], FALSE, FALSE],
];

$nodes = [];
foreach ($devices as [$name, $slug, $make, $type, $line, $lease, $buy, $order, $industries, $dual, $featured]) {
  $fields = [
    'field_manufacturer' => $term('manufacturer', $make),
    'field_device_type' => $term('device_type', $type),
    'field_sales_channels' => $terms('sales_channel', $channels[$type]),
    'field_industries' => $terms('industry', $industries),
    'field_card_summary' => $line,
    'field_lease_price' => $lease,
    'field_buy_price' => $buy,
    'field_in_stock' => 1,
    'field_dual_pricing_ready' => (int) $dual,
    'field_featured' => (int) $featured,
    'field_sort_weight' => $order,
  ];
  // Clover Mini's page details are paragraphs, which are saved as soon as
  // they are built, so build them only when the device is being created.
  if ($slug === 'clover-mini' && !ib_node_by_alias('/hardware/' . $slug)) {
    $spec = fn(string $label, string $value) => ib_paragraph('spec_row', ['label' => $label, 'spec_value' => $value]);
    $fields += [
      'field_summary' => 'A full point of sale on a small footprint. Take every way to pay, track inventory and sales, and add the apps you need, all from an 8-inch screen.',
      'field_lease_terms' => 'Month to month, with the starter kit and 3-year protection included.',
      'field_buy_terms' => 'One payment and the device is yours, with the starter kit and 3-year protection included.',
      'field_key_features' => ['Tap, chip, swipe and EBT', 'Apple Pay and Google Pay', '500+ apps in the Clover App Market', 'Ships programmed for your account'],
      'field_specs' => [
        $spec('Screen', '8-inch touchscreen'),
        $spec('Payments accepted', 'Contactless (Apple Pay, Google Pay), EMV chip with PIN or signature, magstripe, EBT'),
        $spec('Size', '4.2 × 8.3 × 6.7 in.'),
        $spec('Connectivity', 'Wi-Fi, Ethernet and LTE (cellular data plan optional)'),
        $spec('Receipt printer', '[Confirm with current Clover spec sheet]'),
        $spec('Software', 'Register, orders, rewards, customers, reporting and time clock built in'),
        $spec('In the box', 'Clover Mini, starter kit'),
        $spec('Protection', '3-year protection included'),
      ],
      'field_fees' => [
        ib_paragraph('fee_row', ['label' => 'Clover platform fee', 'amount' => 9.95]),
        ib_paragraph('fee_row', ['label' => 'Security and encryption (TransArmor)', 'amount' => 9.95]),
        ib_paragraph('fee_row', ['label' => 'Cellular data, optional', 'amount' => 44.95]),
      ],
      'field_gateways' => [
        ib_paragraph('gateway', ['icon' => 'credit-card', 'title' => 'Clover (native)', 'body' => "Runs on Clover's own gateway with the Clover web dashboard."]),
        ib_paragraph('gateway', ['icon' => 'globe', 'title' => 'Clover virtual terminal', 'body' => 'Key in phone orders and send invoices from any browser.']),
      ],
      'field_gateway_note' => 'Need NMI, Authorize.net or another third-party gateway? Clover devices do not support them. Look at Dejavoo or PAX instead.',
    ];
  }
  $nodes[$slug] = ib_page('device', $name, '/hardware/' . $slug, [], $fields);
}

// Earlier versions of this script built Clover Mini's rows on every run and
// left them unattached. Rows of these types without a parent are never shown,
// so remove them.
$orphans = \Drupal::entityQuery('paragraph')
  ->accessCheck(FALSE)
  ->condition('type', ['spec_row', 'fee_row', 'gateway'], 'IN')
  ->notExists('parent_id')
  ->execute();
if ($orphans) {
  $paragraph_storage = \Drupal::entityTypeManager()->getStorage('paragraph');
  $paragraph_storage->delete($paragraph_storage->loadMultiple($orphans));
  print count($orphans) . " unattached device rows removed.\n";
}

// Clover Mini's related devices, once every device exists.
$mini = $nodes['clover-mini'];
if ($mini->get('field_related_devices')->isEmpty()) {
  $mini->set('field_related_devices', array_map(fn(string $slug) => ['target_id' => $nodes[$slug]->id()], ['clover-flex', 'clover-station-solo', 'clover-compact', 'dejavoo-qd4']));
  $mini->save();
}

// Product photos are attached by 92-device-photos.php.

print count($nodes) . " devices are in place.\n";
