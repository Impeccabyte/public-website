<?php

/**
 * @file
 * Page details for the eleven devices that only had a catalog card.
 *
 * Every claim reuses something the site already says: the catalog card line,
 * the industry setups on Home, the In person and On the go pages, the Pricing
 * note ("Lease is month to month. Clover devices include a starter kit and
 * 3-year protection."), the Clover gateway note on Clover Mini and the dual
 * pricing wireframe's list of ready devices (Z8, QD4, VP550). Maverick's
 * knowledge base lists the A920 Pro for surcharging only, and the VP550 as a
 * mobile terminal rather than a countertop.
 * Nothing comes from a spec sheet, so no device here gets a spec table or fee rows.
 * Those wait for confirmed data.
 *
 * Safe to re-run: a field that already has a value is left alone, so edits
 * made in the admin are kept. Run it with
 *   ./scripts/drush php:script scripts/content/91-device-details.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$wallets = 'Tap, chip and swipe, plus Apple Pay and Google Pay';
$programmed = 'Ships programmed for your account';
$offline = 'Keeps taking payments offline for a period if the connection drops';
$reprogram = 'Already own one? Many can be reprogrammed for your new account';

$clover = [
  'lease_terms' => 'Month to month, with the starter kit and 3-year protection included.',
  'buy_terms' => 'One payment and the device is yours, with the starter kit and 3-year protection included.',
  'gateways' => [
    ['credit-card', 'Clover (native)', "Runs on Clover's own gateway with the Clover web dashboard."],
    ['globe', 'Clover virtual terminal', 'Key in phone orders and send invoices from any browser.'],
  ],
  'gateway_note' => 'Need NMI, Authorize.net or another third-party gateway? Clover devices do not support them. Look at Dejavoo or PAX instead.',
];
$other = [
  'lease_terms' => 'Month to month.',
  'buy_terms' => 'One payment and the device is yours.',
];

// Alias => summary, key features, related devices, shared terms.
$devices = [
  'clover-flex' => [
    'A handheld that takes payment wherever your customer is: at the table, at the curb or at the truck window. It prints receipts, runs on Wi-Fi or cellular, and lets servers split a check by item, by seat or evenly.',
    ['Tableside and curbside pay', 'Built-in receipt printer', 'Runs on Wi-Fi or cellular (data plan optional)', 'Split checks by item, by seat or evenly', $offline, $programmed],
    ['pax-a920-pro', 'clover-go', 'clover-station-duo'],
    $clover,
  ],
  'clover-station-duo' => [
    "Clover's dual-screen flagship for busy counters: one screen for your team and one facing your customer, so orders and payments keep moving at the rush.",
    ['Two screens, one for staff and one for customers', $wallets, 'DoorDash orders land on the POS and print in the kitchen', $offline, $programmed],
    ['clover-station-solo', 'clover-mini', 'clover-flex', 'clover-kiosk'],
    $clover,
  ],
  'clover-station-solo' => [
    'A big-screen Clover POS with a receipt printer, built for full-service counters and dining rooms that need the whole register in view.',
    ['Big touchscreen for the full register', 'Receipt printer included', $wallets, 'DoorDash orders land on the POS and print in the kitchen', $offline, $programmed],
    ['clover-station-duo', 'clover-mini', 'clover-flex'],
    $clover,
  ],
  'clover-compact' => [
    'A small, simple Clover terminal for tight counters. It fits a crowded front desk or styling station and still takes every common way to pay.',
    ['Small footprint for tight counters', $wallets, $offline, $programmed],
    ['dejavoo-z8', 'valor-vp550', 'clover-mini', 'dejavoo-qd4'],
    $clover,
  ],
  'clover-go' => [
    'A pocket-size Clover reader that pairs with your phone or tablet by Bluetooth. Take chip, tap and swipe at the job site, the market or the event table.',
    ['Chip, tap and swipe', 'Pairs with your phone or tablet by Bluetooth', "Uses your phone's connection, so no data plan", $programmed],
    ['swipesimple-b350', 'clover-flex', 'pax-a920-pro'],
    $clover,
  ],
  'clover-kiosk' => [
    'A 24-inch self-order screen for counter-service spots. Customers order and pay on their own, which shortens the line at the counter.',
    ['24-inch self-order screen', 'Customers order and pay on their own', $wallets, $programmed],
    ['clover-station-duo', 'clover-flex', 'clover-mini'],
    $clover,
  ],
  'dejavoo-z8' => [
    'A budget-friendly countertop terminal that runs on Wi-Fi and comes ready for dual pricing. A dependable pick for front desks and service counters.',
    ['Runs on Wi-Fi', 'Dual pricing ready', $wallets, $reprogram, $programmed],
    ['clover-compact', 'valor-vp550', 'dejavoo-qd4'],
    $other,
  ],
  'dejavoo-qd4' => [
    'An Android smart terminal with a large touchscreen, for counters that want a modern checkout without a full POS station.',
    ['Android smart terminal', '5.5-inch touchscreen', 'Built-in receipt printer', 'Dual pricing ready', $wallets, $reprogram, $programmed],
    ['dejavoo-z8', 'valor-vp550', 'clover-compact'],
    $other,
  ],
  'pax-a920-pro' => [
    'A flexible handheld for tableside service and deliveries. It is an all-in-one device with a built-in printer, so you can take payment and hand over a receipt wherever your customer is.',
    ['Tableside and delivery pay', 'Built-in receipt printer', 'Surcharging supported', $wallets, $reprogram, $programmed],
    ['clover-flex', 'clover-go', 'swipesimple-b350'],
    $other,
  ],
  'valor-vp550' => [
    'An Android mobile terminal built for dual pricing, so card customers cover the processing cost. It runs on Wi-Fi or 4G, so it works at the counter, in the bay or out on a service call.',
    ['Built for dual pricing', 'Android mobile terminal', 'Wi-Fi or 4G (data plan optional)', $wallets, $reprogram, $programmed],
    ['valor-vl550', 'dejavoo-qd2', 'pax-a920-pro'],
    $other,
  ],
  'swipesimple-b350' => [
    'Our lowest-cost reader. It pairs with your phone or tablet by Bluetooth for chip, tap and swipe, which makes it an easy start for markets and pop-ups.',
    ['Chip, tap and swipe', 'Pairs with your phone or tablet by Bluetooth', "Uses your phone's connection, so no data plan", $programmed],
    ['clover-go', 'clover-flex', 'pax-a920-pro'],
    $other,
  ],
];

$filled = 0;
foreach ($devices as $slug => [$summary, $features, $related, $terms]) {
  $node = ib_node_by_alias('/hardware/' . $slug);
  if (!$node) {
    print "Missing device: $slug. Run 30-devices.php first.\n";
    continue;
  }
  $changed = FALSE;
  $set = function (string $field, $value) use ($node, &$changed) {
    if ($value && $node->get($field)->isEmpty()) {
      $node->set($field, $value);
      $changed = TRUE;
    }
  };
  $set('field_summary', $summary);
  $set('field_key_features', $features);
  $set('field_lease_terms', $terms['lease_terms'] ?? NULL);
  $set('field_buy_terms', $terms['buy_terms'] ?? NULL);
  $set('field_gateway_note', $terms['gateway_note'] ?? NULL);
  if (!empty($terms['gateways']) && $node->get('field_gateways')->isEmpty()) {
    $set('field_gateways', array_map(fn(array $g) => ib_paragraph('gateway', ['icon' => $g[0], 'title' => $g[1], 'body' => $g[2]]), $terms['gateways']));
  }
  $refs = array_values(array_filter(array_map(fn(string $alias) => ib_node_by_alias('/hardware/' . $alias)?->id(), $related)));
  $set('field_related_devices', array_map(fn($id) => ['target_id' => $id], $refs));
  if ($changed) {
    $node->save();
    $filled++;
  }
}

print "Device details filled on $filled of " . count($devices) . " devices.\n";
