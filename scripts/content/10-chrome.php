<?php

/**
 * @file
 * First content for the global chrome: menu links and the mega menu's
 * featured card.
 *
 * Safe to re-run: links are matched by menu, parent and title, and updated
 * only when something differs. Run it with
 *   ./scripts/drush php:script scripts/content/10-chrome.php
 */

use Drupal\block_content\Entity\BlockContent;

require_once dirname(__DIR__) . '/setup/_helpers.php';

$term_filter = function (string $base, string $facet, string $vid, string $name): string {
  $tid = ib_term_id($vid, $name);
  // Checkbox filters read their values as facet[id]=id.
  return $tid ? "internal:$base?{$facet}[$tid]=$tid" : "internal:$base";
};

// Main menu. Solutions are grouped under the three solution categories.
// Business banking is out of scope at launch, so it has no link.
$solutions = ib_menu_link('main', 'Solutions', 'route:<nolink>', ['weight' => 0]);
$accept = ib_menu_link('main', 'Accept Payments', 'route:<nolink>', ['parent' => $solutions, 'weight' => 0]);
ib_menu_link('main', 'In Person', 'internal:/solutions/in-person', ['parent' => $accept, 'weight' => 0, 'icon' => 'store', 'description' => 'Countertop terminals and full POS systems']);
ib_menu_link('main', 'Online', 'internal:/solutions/online', ['parent' => $accept, 'weight' => 1, 'icon' => 'globe', 'description' => 'Gateways, hosted checkout and payment links']);
ib_menu_link('main', 'On the Go', 'internal:/solutions/on-the-go', ['parent' => $accept, 'weight' => 2, 'icon' => 'smartphone', 'description' => 'Mobile readers and tap to pay on your phone']);
ib_menu_link('main', 'Recurring Billing', 'internal:/solutions/recurring-billing', ['parent' => $accept, 'weight' => 3, 'icon' => 'repeat', 'description' => 'Subscriptions, invoices and card updater']);
$lower = ib_menu_link('main', 'Lower Your Costs', 'route:<nolink>', ['parent' => $solutions, 'weight' => 1]);
ib_menu_link('main', 'Dual Pricing', 'internal:/solutions/dual-pricing', ['parent' => $lower, 'weight' => 0, 'icon' => 'circle-percent', 'description' => 'Show a cash and card price and keep more of each sale']);
ib_menu_link('main', 'Interchange Plus', 'internal:/solutions/interchange-plus', ['parent' => $lower, 'weight' => 1, 'icon' => 'chart-line', 'description' => 'Pass-through pricing with one clear markup']);
ib_menu_link('main', 'Chargeback Protection', 'internal:/solutions/chargeback-protection', ['parent' => $lower, 'weight' => 2, 'icon' => 'shield-check', 'description' => 'Alerts and dispute help before losses stack up']);
$run = ib_menu_link('main', 'Run Your Business', 'route:<nolink>', ['parent' => $solutions, 'weight' => 2]);
ib_menu_link('main', 'Integrations', 'internal:/solutions/integrations', ['parent' => $run, 'weight' => 0, 'icon' => 'plug', 'description' => 'Connect invoicing, accounting and your POS']);
ib_menu_link('main', 'Funding', 'internal:/solutions/funding', ['parent' => $run, 'weight' => 1, 'icon' => 'zap', 'description' => 'How and when deposits reach your bank']);

$hardware = ib_menu_link('main', 'Hardware', 'internal:/hardware', ['weight' => 1]);
ib_menu_link('main', 'All Devices', 'internal:/hardware', ['parent' => $hardware, 'weight' => 0, 'icon' => 'credit-card', 'description' => 'Every terminal, reader and POS system we set up']);
$device_types = [
  'Countertop Terminals' => ['Countertop Terminal', 'store', 'Reliable terminals for the front counter'],
  'Handheld Terminals' => ['Handheld', 'smartphone', 'Take payment at the table or curb'],
  'POS Systems' => ['POS System', 'calculator', 'Run orders, inventory and payments together'],
  'Mobile Readers' => ['Mobile Reader', 'nfc', 'Pocket readers that pair with your phone'],
  'Self-Order Kiosks' => ['Self-Order Kiosk', 'users', 'Let customers order and pay on their own'],
];
$weight = 1;
foreach ($device_types as $title => [$term, $icon, $description]) {
  ib_menu_link('main', $title, $term_filter('/hardware', 'type', 'device_type', $term), ['parent' => $hardware, 'weight' => $weight++, 'icon' => $icon, 'description' => $description]);
}

$industries = ib_menu_link('main', 'Industries', 'route:<nolink>', ['weight' => 2]);
$industry_links = [
  'Restaurants & Bars' => ['/industries/restaurants', 'utensils'],
  'Food Trucks' => ['/industries/food-trucks', 'truck'],
  'Retail' => ['/industries/retail', 'store'],
  'Salons & Spas' => ['/industries/salons-and-spas', 'sparkles'],
  'Home Services' => ['/industries/home-services', 'plug'],
  'Healthcare & Dental' => ['/industries/healthcare', 'heart-pulse'],
  'Auto Repair' => ['/industries/auto-repair', 'zap'],
  'Professional Services' => ['/industries/professional-services', 'file-text'],
  'Nonprofits' => ['/industries/nonprofits', 'users'],
  'Pop-Ups & Markets' => ['/industries/pop-ups-and-markets', 'map-pin'],
];
$weight = 0;
foreach ($industry_links as $title => [$path, $icon]) {
  ib_menu_link('main', $title, 'internal:' . $path, ['parent' => $industries, 'weight' => $weight++, 'icon' => $icon]);
}

ib_menu_link('main', 'Pricing', 'internal:/pricing', ['weight' => 3]);

$resources = ib_menu_link('main', 'Resources', 'internal:/resources', ['weight' => 4]);
ib_menu_link('main', 'All resources', 'internal:/resources', ['parent' => $resources, 'weight' => 0, 'icon' => 'book-open', 'description' => 'Guides and answers in plain English']);
$resource_types = [
  'Guides' => ['book-open', 'Step-by-step help for common jobs'],
  'Checklists' => ['list-checks', 'Short lists to work through'],
  'Videos' => ['play', 'Quick walkthroughs you can watch'],
  'Customer stories' => ['message-circle', 'How local businesses set things up'],
  'Glossary' => ['file-text', 'Payments terms in plain words'],
];
$weight = 1;
foreach ($resource_types as $title => [$icon, $description]) {
  // The resource library's type filter is a single choice: type=id.
  $tid = ib_term_id('resource_type', $title);
  ib_menu_link('main', $title, 'internal:/resources' . ($tid ? '?type=' . $tid : ''), ['parent' => $resources, 'weight' => $weight++, 'icon' => $icon, 'description' => $description]);
}

$company = ib_menu_link('main', 'Company', 'route:<nolink>', ['weight' => 5]);
ib_menu_link('main', 'About Us', 'internal:/about', ['parent' => $company, 'weight' => 0, 'icon' => 'users', 'description' => 'A local Austin team']);
ib_menu_link('main', 'Contact', 'internal:/get-a-quote', ['parent' => $company, 'weight' => 1, 'icon' => 'message-circle', 'description' => 'Talk to a local specialist']);
ib_menu_link('main', 'Merchant Log In', 'https://app.impeccabyte.com', ['parent' => $company, 'weight' => 2, 'icon' => 'lock', 'description' => 'Open the merchant app']);

// Footer columns.
$footer = [
  'footer-solutions' => [
    'In-Person Payments' => 'internal:/solutions/in-person',
    'Online Payments' => 'internal:/solutions/online',
    'Mobile Payments' => 'internal:/solutions/on-the-go',
    'Dual Pricing' => 'internal:/solutions/dual-pricing',
    'Chargeback Protection' => 'internal:/solutions/chargeback-protection',
  ],
  'footer-hardware' => [
    'All Devices' => 'internal:/hardware',
    'Countertop Terminals' => $term_filter('/hardware', 'type', 'device_type', 'Countertop Terminal'),
    'Handheld Terminals' => $term_filter('/hardware', 'type', 'device_type', 'Handheld'),
    'POS Systems' => $term_filter('/hardware', 'type', 'device_type', 'POS System'),
    'Mobile Readers' => $term_filter('/hardware', 'type', 'device_type', 'Mobile Reader'),
  ],
  'footer-industries' => [
    'Restaurants & Bars' => 'internal:/industries/restaurants',
    'Retail' => 'internal:/industries/retail',
    'Salons & Spas' => 'internal:/industries/salons-and-spas',
    'Home Services' => 'internal:/industries/home-services',
    'Food Trucks' => 'internal:/industries/food-trucks',
  ],
  'footer-company' => [
    'About Us' => 'internal:/about',
    'Pricing' => 'internal:/pricing',
    'Resources' => 'internal:/resources',
    'Contact' => 'internal:/get-a-quote',
    'Merchant Log In' => 'https://app.impeccabyte.com',
  ],
  'footer-legal' => [
    'Privacy' => 'internal:/privacy',
    'Terms' => 'internal:/terms',
    'Accessibility' => 'internal:/accessibility',
    'Sitemap' => 'internal:/sitemap.xml',
  ],
];
foreach ($footer as $menu => $links) {
  $weight = 0;
  foreach ($links as $title => $uri) {
    ib_menu_link($menu, $title, $uri, ['weight' => $weight++]);
  }
}

// The mega menu's featured card.
$uuid = 'b1f0a8e2-6a53-4c0e-9d7a-5e1c3a7f2d41';
if (!\Drupal::service('entity.repository')->loadEntityByUuid('block_content', $uuid)) {
  BlockContent::create([
    'uuid' => $uuid,
    'type' => 'featured_card',
    'info' => 'Mega menu: founder guide',
    'field_badge' => "Founder's guide",
    'field_heading' => 'New to taking cards?',
    'field_body' => 'Our starter guide walks you through your first merchant account in plain English, from paperwork to your first sale.',
    'field_link' => ['uri' => 'internal:/resources', 'title' => 'Read the guide'],
  ])->save();
}
// Renamed from "Founder guide" on 2026-11-19.
$card = \Drupal::service('entity.repository')->loadEntityByUuid('block_content', $uuid);
if ($card && $card->get('field_badge')->value === 'Founder guide') {
  $card->set('field_badge', "Founder's guide")->save();
}

print "Chrome content is in place.\n";
