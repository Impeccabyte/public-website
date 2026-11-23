<?php

/**
 * @file
 * Spec tables for the Clover devices, from clover.com (read 2026-11-23).
 *
 * Maverick's knowledge base has no Clover spec sheets, so each table repeats
 * only what the device's own "Product details" on clover.com states
 * (clover.com/flex, /flex-pocket, /station-duo, /station-solo, /compact,
 * /kiosk; Clover Go's "reader details" on clover.com/go), worded for
 * merchants. Clover Mini's table comes from scripts/content/30-devices.php.
 *
 * A device that already has a spec table is left alone, so edits made in the
 * admin are kept. Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-clover-specs.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$wallets = 'Chip, swipe and tap, including Apple Pay, Google Pay and Samsung Pay';
$security = 'End-to-end encryption (Clover Security)';
$drawer = 'Bill tray, coin tray, two keys and a security cable';

$specs = [
  'clover-flex' => [
    ['Payments accepted', $wallets],
    ['Screen', 'Nearly 6-inch color touchscreen'],
    ['Receipts', 'Built-in thermal receipt printer, or send receipts by email or text'],
    ['Scanner', '1D and 2D barcode scanner and camera'],
    ['Connectivity', 'Wi-Fi and LTE'],
    ['Battery', 'At least 8 hours of typical use'],
    ['Security', $security],
  ],
  'clover-flex-pocket' => [
    ['Payments accepted', $wallets],
    ['Screen', '5.99-inch color touchscreen'],
    ['Receipts', 'No printer: send receipts by text or email'],
    ['Scanner', '1D and 2D barcode scanner and camera'],
    ['Connectivity', 'Wi-Fi and LTE'],
    ['Battery', 'At least 8 hours of use on average, with USB-C charging'],
    ['Security', $security],
  ],
  'clover-station-duo' => [
    ['Payments accepted', $wallets],
    ['Screens', '14-inch HD staff touchscreen and 8-inch customer touchscreen'],
    ['PIN entry', 'On-screen keypad on the customer screen'],
    ['Receipt printer', 'High-speed thermal receipt printer'],
    ['Cameras', 'Two 5-megapixel cameras with scanning software'],
    ['Connectivity', 'Wi-Fi, Ethernet and 4G LTE'],
    ['Cash drawer', $drawer],
    ['Security', $security],
  ],
  'clover-station-solo' => [
    ['Payments accepted', 'Chip and swipe'],
    ['Screen', '14-inch HD display that tilts'],
    ['Receipt printer', 'High-speed thermal receipt printer'],
    ['Camera', 'One 5-megapixel camera with scanning software'],
    ['Connectivity', 'Wi-Fi, Ethernet and 4G LTE'],
    ['Cash drawer', $drawer],
    ['Security', $security],
  ],
  'clover-compact' => [
    ['Payments accepted', 'Tap, chip, swipe and PIN debit'],
    ['Screen', '3.6-inch payment touchscreen'],
    ['Receipt printer', 'Built-in thermal receipt printer'],
    ['Connectivity', 'Wi-Fi, Ethernet and LTE'],
    ['Security', 'End-to-end encryption (Clover Security) and an anti-theft lock'],
  ],
  'clover-go' => [
    ['Payments accepted', $wallets],
    ['Connectivity', 'Bluetooth to your phone or tablet'],
    ['Charging', 'Micro USB'],
    ['Battery', 'About 160 chip, 160 swipe or 130 tap payments per charge'],
    ['Works with', 'iPhone (iOS 15 or later) and Android 9 or later, with Bluetooth LE'],
  ],
  'clover-kiosk' => [
    ['Payments accepted', 'Tap, chip, swipe and PIN debit'],
    ['Screens', '24-inch ordering touchscreen and 8-inch payment touchscreen'],
    ['Receipt printer', 'Built-in thermal receipt printer'],
    ['Connectivity', 'Wi-Fi, Ethernet and LTE'],
    ['Mounting', 'Optional floor stand or wall bracket; fits standard 100 mm VESA mounts'],
    ['Security', $security],
  ],
];

$added = [];
foreach ($specs as $slug => $rows) {
  $node = ib_node_by_alias('/hardware/' . $slug);
  if (!$node || !$node->get('field_specs')->isEmpty()) {
    continue;
  }
  $node->set('field_specs', array_map(fn(array $row) => ib_paragraph('spec_row', ['label' => $row[0], 'spec_value' => $row[1]]), $rows))->save();
  $added[] = $node->label();
}

print $added ? 'Clover spec tables added: ' . implode(', ', $added) . ".\n" : "Clover spec tables: nothing to add.\n";
