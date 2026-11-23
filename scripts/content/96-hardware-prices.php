<?php

/**
 * @file
 * Reg-tier merchant prices for the 14 devices from Maverick's lineup that
 * the pricing playbook (November 2026) does not list, set on 2026-11-22 to
 * match the playbook's Reg tier. Listed as open item 2.1 in
 * docs/content-audit.md until confirmed.
 *
 * Only merchant prices are stored here. Like every device price, they hide
 * behind "Pricing coming soon" while "Show prices" is off.
 *
 * A price is only set where the field is empty, so anything entered in the
 * admin is kept. Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/96-hardware-prices.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Device alias => [purchase, monthly lease].
$prices = [
  'dejavoo-qd2' => [789, 40],
  'dejavoo-qd3' => [539, 25],
  'dejavoo-p1' => [419, 20],
  'dejavoo-p3' => [589, 30],
  'dejavoo-p5' => [389, 20],
  'dejavoo-p8' => [569, 30],
  'dejavoo-p18' => [839, 40],
  'valor-vp100' => [369, 20],
  'valor-vp800' => [1019, 50],
  'valor-vl550' => [599, 30],
  'pax-a80' => [489, 25],
  'pax-a35' => [589, 30],
  'fiserv-fd150' => [729, 35],
  'clover-flex-pocket' => [779, 40],
];

$set = 0;
foreach ($prices as $slug => [$buy, $lease]) {
  $node = ib_node_by_alias('/hardware/' . $slug);
  if (!$node) {
    print "Not found: /hardware/$slug\n";
    continue;
  }
  $changed = FALSE;
  foreach (['field_buy_price' => $buy, 'field_lease_price' => $lease] as $field => $value) {
    if ($node->get($field)->isEmpty()) {
      $node->set($field, number_format($value, 2, '.', ''));
      $changed = TRUE;
    }
  }
  if ($changed) {
    $node->save();
    $set++;
  }
}

print "Hardware prices: $set device(s) priced.\n";
