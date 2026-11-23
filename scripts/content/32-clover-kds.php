<?php

/**
 * @file
 * The Clover Kitchen Display System, 14-inch and 24-inch (2026-11-23).
 *
 * Added to the hardware catalog at Ry's request. Every detail comes from
 * clover.com/kitchen-display-system (read 2026-11-23): the two screen sizes,
 * counter or wall mounting for the 14-inch, overhead use for the 24-inch, the
 * optional KB9000 bump bar, runner tickets on any network printer, expo
 * mode, item-level routing, prep time reporting and color-coded order
 * status. Clover's own retail prices are not used. The 14-inch keeps the
 * seed price the Restaurants setup already used ($40/mo or $839); the
 * 24-inch has no price, so it shows "Pricing coming soon" with a quote link.
 * A kitchen display takes no payments, so it gets no gateways and no
 * Clover payment fees (see 96-pricing-programs.php).
 *
 * Runs before 92-device-photos.php, which attaches the photos. Safe to
 * re-run: a device that already exists is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/32-clover-kds.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

ib_term('device_type', 'Kitchen Display', 5);

$term = fn(string $vid, string $name) => ['target_id' => ib_term_id($vid, $name)];
$spec = fn(string $label, string $value) => ib_paragraph('spec_row', ['label' => $label, 'spec_value' => $value]);

$features = [
  'Orders from every channel on one screen',
  'Expo mode and item-level routing',
  'Color-coded order status and prep time reporting',
  'Runner tickets on any network printer',
];
$shared_specs = [
  ['Bump bar', 'Optional KB9000 programmable bump bar'],
  ['Runner tickets', 'Prints to any network-connected printer'],
  ['Payments', 'None. It shows kitchen orders and works with your Clover point of sale'],
];

$devices = [
  'clover-kds-14' => [
    'Clover Kitchen Display 14-inch',
    'Compact kitchen screen for the counter or wall.',
    'A 14-inch kitchen display for kitchens where space is tight. Orders from the register, online and delivery land on one screen, so the line sees every ticket the moment it is placed.',
    [['Screen', '14-inch touchscreen'], ['Mounting', 'Counter top, or a wall with the optional mount']],
    40, 839, 11,
  ],
  'clover-kds-24' => [
    'Clover Kitchen Display 24-inch',
    'Large overhead kitchen screen for busy lines.',
    'A 24-inch kitchen display made for mounting overhead, where the whole team can see it. Pair it with the optional bump bar when touching the screen is not practical.',
    [['Screen', '24-inch touchscreen'], ['Mounting', 'Made for overhead installation']],
    NULL, NULL, 12,
  ],
];

$created = [];
foreach ($devices as $slug => [$name, $line, $summary, $specs, $lease, $buy, $order]) {
  $alias = '/hardware/' . $slug;
  if (ib_node_by_alias($alias)) {
    continue;
  }
  $fields = [
    'field_manufacturer' => $term('manufacturer', 'Clover'),
    'field_device_type' => $term('device_type', 'Kitchen Display'),
    'field_sales_channels' => [$term('sales_channel', 'In store')],
    'field_industries' => [$term('industry', 'Restaurants & Bars')],
    'field_card_summary' => $line,
    'field_summary' => $summary,
    'field_key_features' => $features,
    'field_lease_terms' => 'Month to month.',
    'field_buy_terms' => 'One payment and the device is yours.',
    'field_in_stock' => 1,
    'field_dual_pricing_ready' => 0,
    'field_featured' => 0,
    'field_sort_weight' => $order,
    'field_specs' => array_map(fn(array $row) => $spec($row[0], $row[1]), array_merge($specs, $shared_specs)),
  ];
  if ($lease !== NULL) {
    $fields['field_lease_price'] = $lease;
    $fields['field_buy_price'] = $buy;
  }
  ib_page('device', $name, $alias, [], $fields);
  $created[] = $name;
}

// Each one points to the other and to the Clover registers it works with.
$related = [
  'clover-kds-14' => ['clover-kds-24', 'clover-station-duo', 'clover-mini'],
  'clover-kds-24' => ['clover-kds-14', 'clover-station-duo', 'clover-kiosk'],
];
foreach ($related as $slug => $others) {
  $node = ib_node_by_alias('/hardware/' . $slug);
  if ($node && $node->get('field_related_devices')->isEmpty()) {
    $ids = array_values(array_filter(array_map(fn(string $other) => ib_node_by_alias('/hardware/' . $other)?->id(), $others)));
    $node->set('field_related_devices', array_map(fn($id) => ['target_id' => $id], $ids))->save();
  }
}

print $created ? 'Kitchen displays added: ' . implode(', ', $created) . ".\n" : "Kitchen displays already in place.\n";
