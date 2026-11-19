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
  'Countertop terminal' => ['In store'],
  'POS system' => ['In store'],
  'Self-order kiosk' => ['In store'],
  'Handheld' => ['Tableside', 'On the go'],
  'Mobile reader' => ['On the go'],
];

// Name, alias, manufacturer, type, card line, lease, buy, order, industries,
// dual pricing ready, featured.
$devices = [
  ['Clover Mini', 'clover-mini', 'Clover', 'POS system', '8-inch all-in-one counter station with 500+ apps.', 60, 1189, 1, ['Retail shops'], FALSE, TRUE],
  ['Clover Flex', 'clover-flex', 'Clover', 'Handheld', 'Tableside and curbside pay with a built-in printer.', 50, 1029, 2, ['Restaurants and bars', 'Food trucks'], FALSE, TRUE],
  ['Clover Station Duo', 'clover-station-duo', 'Clover', 'POS system', 'Dual-screen flagship POS for busy counters.', 135, 2739, 3, ['Restaurants and bars'], FALSE, FALSE],
  ['Clover Station Solo', 'clover-station-solo', 'Clover', 'POS system', 'Big-screen POS with printer for full service.', 125, 2469, 4, [], FALSE, FALSE],
  ['Clover Compact', 'clover-compact', 'Clover', 'Countertop terminal', 'A small, simple terminal for tight counters.', 20, 399, 5, ['Salons and spas'], FALSE, FALSE],
  ['Clover Go', 'clover-go', 'Clover', 'Mobile reader', 'Pocket reader that pairs with your phone.', 10, 209, 6, ['Home services', 'Nonprofits'], FALSE, TRUE],
  ['Clover Kiosk', 'clover-kiosk', 'Clover', 'Self-order kiosk', '24-inch self-order screen to shorten the line.', 215, 4259, 7, [], FALSE, FALSE],
  ['Dejavoo Z8', 'dejavoo-z8', 'Dejavoo', 'Countertop terminal', 'Budget-friendly Wi-Fi terminal, dual pricing ready.', 20, 399, 1, ['Healthcare and dental'], TRUE, TRUE],
  ['Dejavoo QD4', 'dejavoo-qd4', 'Dejavoo', 'Countertop terminal', 'Android smart terminal with a large touchscreen.', 25, 499, 2, [], FALSE, FALSE],
  ['PAX A920 Pro', 'pax-a920-pro', 'PAX', 'Handheld', 'Flexible handheld for tableside and delivery.', 35, 749, 1, [], FALSE, FALSE],
  ['Valor VP550', 'valor-vp550', 'Valor', 'Countertop terminal', 'Value Android countertop built for dual pricing.', 30, 599, 1, ['Auto repair'], TRUE, FALSE],
  ['SwipeSimple B350', 'swipesimple-b350', 'SwipeSimple', 'Mobile reader', 'The lowest-cost reader for phones and tablets.', 10, 199, 1, ['Pop-ups and markets'], FALSE, FALSE],
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
  if ($slug === 'clover-mini') {
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

// Clover Mini's related devices, once every device exists.
$mini = $nodes['clover-mini'];
if ($mini->get('field_related_devices')->isEmpty()) {
  $mini->set('field_related_devices', array_map(fn(string $slug) => ['target_id' => $nodes[$slug]->id()], ['clover-flex', 'clover-station-solo', 'clover-compact', 'dejavoo-qd4']));
  $mini->save();
}

// Placeholder gallery art for Clover Mini, so the gallery works before
// product photography arrives. Replace each with a real photo.
if ($mini->get('field_gallery')->isEmpty()) {
  $views = [
    'front' => ['Front view', '<rect x="150" y="95" width="300" height="250" rx="28" fill="#302419"/><rect x="166" y="111" width="268" height="218" rx="14" fill="#faf6ef"/><text x="300" y="205" text-anchor="middle" font-family="Georgia, serif" font-size="38" font-weight="600" fill="#2a211a">$18.75</text><text x="300" y="240" text-anchor="middle" font-family="Helvetica, Arial, sans-serif" font-size="14" fill="#635648">Tap, insert or swipe</text>'],
    'side' => ['Side view', '<rect x="240" y="95" width="120" height="250" rx="28" fill="#302419"/>'],
    'back' => ['Back view', '<path d="M168 120h264a28 28 0 0 1 28 28v152a20 20 0 0 1-20 20H160a20 20 0 0 1-20-20V148a28 28 0 0 1 28-28z" fill="#463424"/>'],
    'box' => ['What is in the box', '<rect x="110" y="150" width="220" height="180" rx="20" fill="#302419"/><rect x="350" y="270" width="90" height="60" rx="14" fill="#e0d6c6"/><rect x="460" y="240" width="60" height="90" rx="14" fill="#c3b7a6"/>'],
  ];
  $directory = 'public://device-placeholders';
  \Drupal::service('file_system')->prepareDirectory($directory, \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY | \Drupal\Core\File\FileSystemInterface::MODIFY_PERMISSIONS);
  $gallery = [];
  foreach ($views as $key => [$label, $shapes]) {
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 440" width="600" height="440" role="img"><title>Clover Mini, ' . strtolower($label) . ' (placeholder)</title><rect width="600" height="440" fill="#ffffff"/>' . $shapes . '<text x="24" y="416" font-family="Helvetica, Arial, sans-serif" font-size="13" font-weight="600" fill="#635648">Product photo · ' . $label . '</text></svg>';
    $uri = \Drupal::service('file_system')->saveData($svg, "$directory/clover-mini-$key.svg", \Drupal\Core\File\FileExists::Replace);
    $file = \Drupal\file\Entity\File::create(['uri' => $uri, 'status' => 1, 'uid' => 1]);
    $file->save();
    $media = \Drupal\media\Entity\Media::create([
      'bundle' => 'image',
      'name' => "Clover Mini: $label (placeholder)",
      'uid' => 1,
      'status' => 1,
      'field_media_image' => ['target_id' => $file->id(), 'alt' => 'Clover Mini, ' . strtolower($label) . '. Placeholder drawing until product photos arrive.'],
    ]);
    $media->save();
    $gallery[] = ['target_id' => $media->id()];
  }
  $mini->set('field_gallery', $gallery);
  $mini->save();
}

print count($nodes) . " devices are in place.\n";
