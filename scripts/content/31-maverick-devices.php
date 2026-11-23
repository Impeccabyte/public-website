<?php

/**
 * @file
 * The rest of the terminals available through Maverick Payments.
 *
 * Source: Maverick's agent knowledge base (kb.maverickpayments.com), read on
 * 2026-11-19: "Core Payment Terminal Solutions" for the Dejavoo, iPOSPays
 * (Dejavoo P-line), Valor, PAX and FD Series terminals, and "Clover Pricing"
 * for the Clover device list. Descriptions, terminal type, connectivity,
 * accepted payments, recommended verticals and supported pricing programs come
 * from there. Terminal costs and the placement program are internal to
 * Maverick and are not used. Clover Flex Pocket's line comes from clover.com.
 *
 * Not added: the Equinox terminal (the knowledge base does not name the
 * model, and it is recommended for lodging only), the Clover Kitchen Display
 * System (not a payment terminal), and POS software platforms (Quantic,
 * DejaPayPro, TouchBistro).
 *
 * Prices are set by scripts/content/96-hardware-prices.php. Like every
 * device, they show "Pricing coming soon" while "Show prices" is off.
 *
 * Safe to re-run: a device that already exists at its address is left alone.
 * Run it with
 *   ./scripts/drush php:script scripts/content/31-maverick-devices.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

ib_term('manufacturer', 'Fiserv', 5);

$term = fn(string $vid, string $name) => ['target_id' => ib_term_id($vid, $name)];
$terms = fn(string $vid, array $names) => array_map(fn($name) => $term($vid, $name), $names);
$spec = fn(string $label, string $value) => ib_paragraph('spec_row', ['label' => $label, 'spec_value' => $value]);

// Industries, from the knowledge base's recommended verticals: retail shops
// (convenience, clothing, smoke), restaurants (quick and full service),
// auto repair (shops and mobile mechanics), and on-the-go merchants (trade
// shows, seasonal sales), which this site calls food trucks and pop-ups.
$counter = ['Retail', 'Restaurants & Bars', 'Auto Repair'];
$mobile = ['Retail', 'Restaurants & Bars', 'Auto Repair', 'Food Trucks', 'Pop-Ups & Markets'];

// Shared spec values, worded for merchants.
$brands = 'Visa, Mastercard, Discover and American Express';
$entry = 'Chip (with PIN), swipe and tap, including Apple Pay and Google Pay';
$pin = 'PIN debit and EBT (cash and food)';
$pin_p = 'PIN debit and EBT (cash and food), including EBT by chip and tap';
$dual = 'Dual pricing, surcharging and cash discount';
$gift_dejavoo = 'Factor4, SAGE, Paya, STS Gift and more';
$gift_valor = 'Factor4 and Valuetec';
$wifi4g = 'Wi-Fi (2.4 GHz) and 4G with an optional data plan';
$wifi4g5 = 'Wi-Fi (2.4 and 5 GHz) and 4G with an optional data plan';
$lan5 = 'Ethernet and Wi-Fi (2.4 and 5 GHz)';
$docked = 'Ethernet when docked, plus Wi-Fi (2.4 and 5 GHz) and 4G with an optional data plan';

$wallets = 'Tap, chip and swipe, plus Apple Pay and Google Pay';
$programmed = 'Ships programmed for your account';
$dual_ready = 'Dual pricing ready';
$specs = fn(string $type, string $connectivity, string $pin_line, string $gift, ?string $programs, array $extra = []) => array_merge(
  [['Terminal type', $type], ['Connectivity', $connectivity], ['Cards accepted', $brands], ['How customers pay', $entry], ['PIN payments', $pin_line]],
  $extra,
  array_filter([$gift ? ['Gift cards', $gift] : NULL, $programs ? ['Pricing programs', $programs] : NULL]),
);

$other_terms = ['lease' => 'Month to month.', 'buy' => 'One payment and the device is yours.'];
$clover_terms = ['lease' => 'Month to month, with the starter kit and 3-year protection included.', 'buy' => 'One payment and the device is yours, with the starter kit and 3-year protection included.'];

// Name, alias, manufacturer, type, card line, sort order, industries, dual
// pricing ready, summary, key features, spec rows, related devices, terms.
$devices = [
  ['Dejavoo QD2', 'dejavoo-qd2', 'Dejavoo', 'Handheld', 'Wireless Android handheld for the table and on the go.', 3, $mobile, TRUE,
    'A wireless Android handheld for tableside service and selling on the go. It connects over Wi-Fi or 4G, so it keeps taking cards wherever you are.',
    ['Wireless Android handheld', 'Wi-Fi or 4G (data plan optional)', $dual_ready, $wallets, $programmed],
    $specs('Wireless handheld', $wifi4g, $pin, $gift_dejavoo, $dual),
    ['dejavoo-qd3', 'valor-vp550', 'pax-a920-pro'], $other_terms],
  ['Dejavoo QD3', 'dejavoo-qd3', 'Dejavoo', 'Handheld', 'Compact Android mPOS that goes where you do.', 4, $mobile, TRUE,
    "A compact, light Android device for merchants on the move, so you can take payment without carrying a bulky terminal. Pair it with Dejavoo's Denovo software to send customers digital receipts.",
    ['Compact and light', 'Wi-Fi or 4G (data plan optional)', 'Digital receipts with Denovo', $dual_ready, $wallets],
    $specs('Wireless handheld (mPOS)', $wifi4g, $pin, $gift_dejavoo, $dual),
    ['dejavoo-p5', 'dejavoo-qd2', 'clover-go'], $other_terms],
  ['Dejavoo P1', 'dejavoo-p1', 'Dejavoo', 'Countertop Terminal', 'Android countertop with a simple, fast interface.', 5, $counter, TRUE,
    "The countertop terminal in Dejavoo's P-line, with fast processing, quick downloads and a simple interface your team can pick up easily.",
    ['Android countertop terminal', 'Ethernet or Wi-Fi', $dual_ready, 'EBT by chip and tap', $wallets, $programmed],
    $specs('Countertop', $lan5, $pin_p, $gift_dejavoo, $dual),
    ['dejavoo-z8', 'valor-vp100', 'pax-a80'], $other_terms],
  ['Dejavoo P3', 'dejavoo-p3', 'Dejavoo', 'Handheld', 'Rugged wireless terminal for the table or the road.', 6, $mobile, TRUE,
    "The rugged wireless terminal in Dejavoo's P-line, built for merchants on the go and for taking payment at the table.",
    ['Rugged wireless build', 'Wi-Fi or 4G (data plan optional)', $dual_ready, 'EBT by chip and tap', $wallets, $programmed],
    $specs('Wireless handheld', $wifi4g5, $pin_p, $gift_dejavoo, $dual),
    ['dejavoo-p8', 'dejavoo-qd2', 'clover-flex'], $other_terms],
  ['Dejavoo P5', 'dejavoo-p5', 'Dejavoo', 'Handheld', 'Compact wireless mPOS for tableside and mobile pay.', 7, $mobile, TRUE,
    "The compact wireless device in Dejavoo's P-line. Small enough to carry all day, for tableside service and selling on the go.",
    ['Compact wireless mPOS', 'Wi-Fi or 4G (data plan optional)', $dual_ready, 'EBT by chip and tap', $wallets, $programmed],
    $specs('Wireless handheld (mPOS)', $wifi4g5, $pin_p, $gift_dejavoo, $dual),
    ['dejavoo-qd3', 'dejavoo-p8', 'clover-go'], $other_terms],
  ['Dejavoo P8', 'dejavoo-p8', 'Dejavoo', 'Handheld', 'Lightweight touchscreen handheld for tableside pay.', 8, $mobile, TRUE,
    "The lightweight touchscreen handheld in Dejavoo's P-line, made for tableside payments and merchants on the go.",
    ['Lightweight touchscreen handheld', 'Wi-Fi or 4G (data plan optional)', $dual_ready, 'EBT by chip and tap', $wallets, $programmed],
    $specs('Wireless handheld', $wifi4g5, $pin_p, $gift_dejavoo, $dual),
    ['dejavoo-p3', 'clover-flex', 'valor-vl550'], $other_terms],
  ['Dejavoo P18', 'dejavoo-p18', 'Dejavoo', 'Countertop Terminal', 'Countertop tablet that lifts off its base to go mobile.', 9, $mobile, TRUE,
    'An all-in-one tablet with a built-in chip and tap reader. On its base it works as a countertop terminal and tilts to face your customer; lift it off to take payment anywhere in your location.',
    ['Tablet with a built-in chip and tap reader', 'Tilts to face the customer for tips, receipts and the cash and card price', 'Lifts off the base to take payment on site', 'Ethernet when docked, Wi-Fi or 4G', $dual_ready],
    $specs('Countertop when docked, handheld when lifted off', $docked, $pin_p, $gift_dejavoo, $dual, [['Customer screen', 'Tilts on its base so customers can add a tip, enter a phone or email for a digital receipt and see the cash and card price']]),
    ['valor-vp800', 'clover-mini', 'dejavoo-qd4'], $other_terms],
  ['Valor VP100', 'valor-vp100', 'Valor', 'Countertop Terminal', "Valor's flagship countertop, ready for dual pricing.", 2, $counter, TRUE,
    "Valor's flagship countertop terminal: quick to set up, dependable day to day, and backed by Valor's payment gateway.",
    ['Countertop Terminal', 'Ethernet or Wi-Fi', $dual_ready, 'PIN debit and EBT', $wallets, $programmed],
    $specs('Countertop', 'Ethernet and Wi-Fi (2.4 GHz)', $pin, $gift_valor, $dual),
    ['dejavoo-z8', 'dejavoo-p1', 'clover-compact'], $other_terms],
  ['Valor VP800', 'valor-vp800', 'Valor', 'Countertop Terminal', 'Dual-screen terminal that doubles as a simple POS.', 3, ['Retail', 'Restaurants & Bars', 'Food Trucks', 'Pop-Ups & Markets'], TRUE,
    'A smart all-in-one terminal that works on its own or as a simple POS. A second screen faces your customer to show the itemized order, the cash and card price and any surcharge, and to take tips and PINs. Lift it off its base to take payment around your location.',
    ['Two screens, one facing the customer', 'Shows the itemized order and the cash and card price', 'Customers enter tips and PINs on their own screen', 'Lifts off the base to take payment on site', $dual_ready],
    $specs('Countertop when docked, handheld when lifted off', $docked, $pin, $gift_valor, $dual, [['Screens', 'Two: one for staff and one facing the customer']]),
    ['dejavoo-p18', 'clover-station-duo', 'valor-vp100'], $other_terms],
  ['Valor VL550', 'valor-vl550', 'Valor', 'Handheld', 'Android handheld that also takes fleet cards.', 4, $mobile, TRUE,
    'An Android mobile terminal backed by the Valor virtual terminal. It runs on Wi-Fi or 4G and takes fleet cards, which suits mobile mechanics and vehicle service businesses.',
    ['Android mobile terminal', 'Wi-Fi or 4G (data plan optional)', 'Takes fleet cards such as WEX and Voyager', $dual_ready, $wallets],
    $specs('Wireless handheld', $wifi4g5, $pin, '', $dual, [['Fleet cards', 'WEX, Voyager, FuelMan, FleetOne, Visa Fleet and Mastercard Fleet']]),
    ['valor-vp550', 'dejavoo-p8', 'pax-a920-pro'], $other_terms],
  ['PAX A80', 'pax-a80', 'PAX', 'Countertop Terminal', 'Android countertop, on its own or linked to your POS.', 2, $counter, FALSE,
    "The Android countertop terminal in PAX's A-series. Use it on its own, or connect it to one of the many POS systems that work with PAX.",
    ['Android countertop terminal', 'Works on its own or with a connected POS', 'Ethernet or Wi-Fi', 'PIN debit and EBT', $wallets, $programmed],
    $specs('Countertop', $lan5, $pin, 'Factor4 and more', 'Surcharging'),
    ['pax-a35', 'dejavoo-p1', 'valor-vp100'], $other_terms],
  ['PAX A35', 'pax-a35', 'PAX', 'Countertop Terminal', 'Customer-facing PIN pad for busy POS checkouts.', 3, ['Retail', 'Restaurants & Bars'], FALSE,
    'A PIN pad that connects to your POS system, built to keep up at high-volume counters. It replaces the PAX S300.',
    ['PIN pad that connects to your POS', 'Built for high-volume checkouts', 'Ethernet or Wi-Fi', 'PIN debit and EBT', $wallets],
    $specs('PIN pad, connected to your POS', $lan5, $pin, '', 'Surcharging'),
    ['pax-a80', 'clover-station-solo', 'dejavoo-p1'], $other_terms],
  ['Fiserv FD150', 'fiserv-fd150', 'Fiserv', 'Countertop Terminal', 'Reliable countertop that also covers hotels and fuel.', 1, $counter, FALSE,
    'A dependable countertop terminal that also covers businesses many terminals do not, from hotels and equipment rental to fuel sales. Add the RP10 PIN pad so customers keep their card in hand at checkout.',
    ['Countertop Terminal', 'Ethernet, Wi-Fi or dial-up', 'Takes fleet cards', 'Optional RP10 customer PIN pad', $wallets],
    $specs('Countertop', 'Ethernet, Wi-Fi (2.4 GHz) and dial-up', $pin, 'Fiserv gift cards', NULL, [['Fleet cards', 'Accepted'], ['Customer PIN pad', 'Optional RP10, so customers insert or tap their card and enter their PIN themselves']]),
    ['valor-vp100', 'pax-a80', 'dejavoo-z8'], $other_terms],
  ['Clover Flex Pocket', 'clover-flex-pocket', 'Clover', 'Handheld', 'Lightweight Clover handheld without a printer.', 8, ['Restaurants & Bars', 'Retail'], FALSE,
    'A lighter Clover handheld without a built-in printer, for taking orders and payments on the floor or on the go.',
    ['Lightweight handheld', 'No built-in printer', 'Keeps taking payments offline for a period if the connection drops', $programmed],
    [],
    ['clover-flex', 'clover-go', 'dejavoo-p8'], $clover_terms + ['gateways' => TRUE]],
];

$created = 0;
$nodes = [];
foreach ($devices as [$name, $slug, $make, $type, $line, $order, $industries, $dual_ok, $summary, $features, $spec_rows, $related, $device_terms]) {
  $alias = '/hardware/' . $slug;
  if ($node = ib_node_by_alias($alias)) {
    $nodes[$slug] = [$node, $related];
    continue;
  }
  $channels = match ($type) {
    'Handheld' => ['Tableside', 'On the go'],
    default => str_contains($spec_rows[0][1] ?? '', 'handheld when lifted') ? ['In store', 'Tableside'] : ['In store'],
  };
  $fields = [
    'field_manufacturer' => $term('manufacturer', $make),
    'field_device_type' => $term('device_type', $type),
    'field_sales_channels' => $terms('sales_channel', $channels),
    'field_industries' => $terms('industry', $industries),
    'field_card_summary' => $line,
    'field_summary' => $summary,
    'field_key_features' => $features,
    'field_lease_terms' => $device_terms['lease'],
    'field_buy_terms' => $device_terms['buy'],
    'field_in_stock' => 1,
    'field_dual_pricing_ready' => (int) $dual_ok,
    'field_featured' => 0,
    'field_sort_weight' => $order,
    'field_specs' => array_map(fn(array $row) => $spec($row[0], $row[1]), $spec_rows),
  ];
  if (!empty($device_terms['gateways'])) {
    $fields['field_gateways'] = [
      ib_paragraph('gateway', ['icon' => 'credit-card', 'title' => 'Clover (native)', 'body' => "Runs on Clover's own gateway with the Clover web dashboard."]),
      ib_paragraph('gateway', ['icon' => 'globe', 'title' => 'Clover virtual terminal', 'body' => 'Key in phone orders and send invoices from any browser.']),
    ];
    $fields['field_gateway_note'] = 'Need NMI, Authorize.net or another third-party gateway? Clover devices do not support them. Look at Dejavoo or PAX instead.';
  }
  $nodes[$slug] = [ib_page('device', $name, $alias, [], $fields), $related];
  $created++;
}

// Related devices, once every device exists.
foreach ($nodes as [$node, $related]) {
  if ($node->get('field_related_devices')->isEmpty()) {
    $refs = array_values(array_filter(array_map(fn(string $slug) => ib_node_by_alias('/hardware/' . $slug)?->id(), $related)));
    if ($refs) {
      $node->set('field_related_devices', array_map(fn($id) => ['target_id' => $id], $refs))->save();
    }
  }
}

print "Maverick devices: $created added, " . (count($devices) - $created) . " already in place.\n";
