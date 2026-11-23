<?php

/**
 * @file
 * First terms for every curated vocabulary, in the order they should list.
 *
 * Terms are content, not config, so an install from config/sync does not
 * create them. This runs before the other content scripts because pages,
 * devices and resources refer to these terms by name.
 *
 * Safe to re-run: a term that already exists is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/05-terms.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$terms = [
  'device_type' => ['Countertop Terminal', 'Handheld', 'Mobile Reader', 'POS System', 'Self-Order Kiosk'],
  'sales_channel' => ['In store', 'Tableside', 'On the go', 'Online and in person'],
  'industry' => ['Restaurants & Bars', 'Food Trucks', 'Retail', 'Salons & Spas', 'Home Services', 'Healthcare & Dental', 'Auto Repair', 'Professional Services', 'Nonprofits', 'Pop-Ups & Markets'],
  // Clover leads: it is the hero hardware.
  'manufacturer' => ['Clover', 'Dejavoo', 'PAX', 'Valor', 'SwipeSimple', 'Fiserv'],
  'solution_category' => ['Accept Payments', 'Lower Your Costs', 'Run Your Business'],
  'resource_type' => ['Guides', 'Checklists', 'Videos', 'Customer stories', 'Glossary'],
  // Merchant services only at launch, so there is no Banking topic.
  'resource_topic' => ['Getting started', 'Pricing and fees', 'Hardware', 'Online payments', 'Chargebacks', 'Funding'],
];
foreach ($terms as $vid => $names) {
  foreach ($names as $weight => $name) {
    ib_term($vid, $name, $weight);
  }
}
echo "First terms are in place.\n";
