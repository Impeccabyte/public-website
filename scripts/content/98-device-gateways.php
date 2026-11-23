<?php

/**
 * @file
 * Gateways on non-Clover device pages, only where Maverick names one
 * (2026-11-23; docs/maverick-offerings.md, sections 4 and 5).
 *
 * - Dejavoo terminals: iPOSPays, Dejavoo's gateway and portal.
 * - Valor terminals: the Valor portal.
 * - SwipeSimple B350: the SwipeSimple gateway, part of SwipeSimple's plans.
 * PAX and the Fiserv FD150 get no gateway section: Maverick names none.
 *
 * A device that already has gateways is left alone, so edits made in the
 * admin are kept. Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-device-gateways.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$note = 'Using a different gateway now? Ask us, and we will confirm what connects before you switch.';
$gateways = [
  'dejavoo' => ['iPOSPays', "Dejavoo's own gateway and portal: transactions, a virtual terminal, invoices, payment links and recurring billing."],
  'valor' => ['Valor portal', "Valor's portal: transactions, a virtual terminal with invoices, payment links and recurring billing, and QuickBooks Online sync."],
  'swipesimple' => ['SwipeSimple gateway', 'Included with SwipeSimple plans: the mobile app, a virtual terminal, invoices and payment links.'],
];
$devices = [
  'dejavoo' => ['dejavoo-z8', 'dejavoo-qd2', 'dejavoo-qd3', 'dejavoo-qd4', 'dejavoo-p1', 'dejavoo-p3', 'dejavoo-p5', 'dejavoo-p8', 'dejavoo-p18'],
  'valor' => ['valor-vp100', 'valor-vp550', 'valor-vp800', 'valor-vl550'],
  'swipesimple' => ['swipesimple-b350'],
];

$added = [];
foreach ($devices as $brand => $slugs) {
  [$title, $body] = $gateways[$brand];
  foreach ($slugs as $slug) {
    $node = ib_node_by_alias('/hardware/' . $slug);
    if (!$node || !$node->get('field_gateways')->isEmpty()) {
      continue;
    }
    $node->set('field_gateways', [ib_paragraph('gateway', ['icon' => 'credit-card', 'title' => $title, 'body' => $body])]);
    if ($node->get('field_gateway_note')->isEmpty()) {
      $node->set('field_gateway_note', $note);
    }
    $node->save();
    $added[] = $node->label();
  }
}

print $added ? 'Device gateways added: ' . implode(', ', $added) . ".\n" : "Device gateways: nothing to add.\n";
