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
$accept = ib_menu_link('main', 'Accept payments', 'route:<nolink>', ['parent' => $solutions, 'weight' => 0]);
ib_menu_link('main', 'In person', 'internal:/solutions/in-person', ['parent' => $accept, 'weight' => 0, 'icon' => 'store', 'description' => 'Countertop terminals and full POS systems']);
ib_menu_link('main', 'Online', 'internal:/solutions/online', ['parent' => $accept, 'weight' => 1, 'icon' => 'globe', 'description' => 'Gateways, hosted checkout and payment links']);
ib_menu_link('main', 'On the go', 'internal:/solutions/on-the-go', ['parent' => $accept, 'weight' => 2, 'icon' => 'smartphone', 'description' => 'Mobile readers and tap to pay on your phone']);
ib_menu_link('main', 'Recurring billing', 'internal:/solutions/recurring-billing', ['parent' => $accept, 'weight' => 3, 'icon' => 'repeat', 'description' => 'Subscriptions, invoices and card updater']);
$lower = ib_menu_link('main', 'Lower your costs', 'route:<nolink>', ['parent' => $solutions, 'weight' => 1]);
ib_menu_link('main', 'Dual pricing', 'internal:/solutions/dual-pricing', ['parent' => $lower, 'weight' => 0, 'icon' => 'circle-percent', 'description' => 'Show a cash and card price and keep more of each sale']);
ib_menu_link('main', 'Interchange plus', 'internal:/solutions/interchange-plus', ['parent' => $lower, 'weight' => 1, 'icon' => 'chart-line', 'description' => 'Pass-through pricing with one clear markup']);
ib_menu_link('main', 'Chargeback protection', 'internal:/solutions/chargeback-protection', ['parent' => $lower, 'weight' => 2, 'icon' => 'shield-check', 'description' => 'Alerts and dispute help before losses stack up']);
$run = ib_menu_link('main', 'Run your business', 'route:<nolink>', ['parent' => $solutions, 'weight' => 2]);
ib_menu_link('main', 'Integrations', 'internal:/solutions/integrations', ['parent' => $run, 'weight' => 0, 'icon' => 'plug', 'description' => 'Connect invoicing, accounting and your POS']);
ib_menu_link('main', 'Funding', 'internal:/solutions/funding', ['parent' => $run, 'weight' => 1, 'icon' => 'zap', 'description' => 'How and when deposits reach your bank']);

$hardware = ib_menu_link('main', 'Hardware', 'internal:/hardware', ['weight' => 1]);
ib_menu_link('main', 'All devices', 'internal:/hardware', ['parent' => $hardware, 'weight' => 0, 'icon' => 'credit-card', 'description' => 'Every terminal, reader and POS system we set up']);
$device_types = [
  'Countertop terminals' => ['Countertop terminal', 'store', 'Reliable terminals for the front counter'],
  'Handheld terminals' => ['Handheld', 'smartphone', 'Take payment at the table or curb'],
  'POS systems' => ['POS system', 'calculator', 'Run orders, inventory and payments together'],
  'Mobile readers' => ['Mobile reader', 'nfc', 'Pocket readers that pair with your phone'],
  'Self-order kiosks' => ['Self-order kiosk', 'users', 'Let customers order and pay on their own'],
];
$weight = 1;
foreach ($device_types as $title => [$term, $icon, $description]) {
  ib_menu_link('main', $title, $term_filter('/hardware', 'type', 'device_type', $term), ['parent' => $hardware, 'weight' => $weight++, 'icon' => $icon, 'description' => $description]);
}

$industries = ib_menu_link('main', 'Industries', 'route:<nolink>', ['weight' => 2]);
$industry_links = [
  'Restaurants and bars' => ['/industries/restaurants', 'utensils'],
  'Food trucks' => ['/industries/food-trucks', 'truck'],
  'Retail shops' => ['/industries/retail', 'store'],
  'Salons and spas' => ['/industries/salons-and-spas', 'sparkles'],
  'Home services' => ['/industries/home-services', 'plug'],
  'Healthcare and dental' => ['/industries/healthcare', 'heart-pulse'],
  'Auto repair' => ['/industries/auto-repair', 'zap'],
  'Professional services' => ['/industries/professional-services', 'file-text'],
  'Nonprofits' => ['/industries/nonprofits', 'users'],
  'Pop-ups and markets' => ['/industries/pop-ups-and-markets', 'map-pin'],
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
ib_menu_link('main', 'About us', 'internal:/about', ['parent' => $company, 'weight' => 0, 'icon' => 'users', 'description' => 'A local Austin team']);
ib_menu_link('main', 'Contact', 'internal:/get-a-quote', ['parent' => $company, 'weight' => 1, 'icon' => 'message-circle', 'description' => 'Talk to a local specialist']);
ib_menu_link('main', 'Merchant log in', 'https://app.impeccabyte.com', ['parent' => $company, 'weight' => 2, 'icon' => 'lock', 'description' => 'Open the merchant app']);

// Footer columns.
$footer = [
  'footer-solutions' => [
    'In-person payments' => 'internal:/solutions/in-person',
    'Online payments' => 'internal:/solutions/online',
    'Mobile payments' => 'internal:/solutions/on-the-go',
    'Dual pricing' => 'internal:/solutions/dual-pricing',
    'Chargeback protection' => 'internal:/solutions/chargeback-protection',
  ],
  'footer-hardware' => [
    'All devices' => 'internal:/hardware',
    'Countertop terminals' => $term_filter('/hardware', 'type', 'device_type', 'Countertop terminal'),
    'Handheld terminals' => $term_filter('/hardware', 'type', 'device_type', 'Handheld'),
    'POS systems' => $term_filter('/hardware', 'type', 'device_type', 'POS system'),
    'Mobile readers' => $term_filter('/hardware', 'type', 'device_type', 'Mobile reader'),
  ],
  'footer-industries' => [
    'Restaurants and bars' => 'internal:/industries/restaurants',
    'Retail shops' => 'internal:/industries/retail',
    'Salons and spas' => 'internal:/industries/salons-and-spas',
    'Home services' => 'internal:/industries/home-services',
    'Food trucks' => 'internal:/industries/food-trucks',
  ],
  'footer-company' => [
    'About us' => 'internal:/about',
    'Pricing' => 'internal:/pricing',
    'Resources' => 'internal:/resources',
    'Contact' => 'internal:/get-a-quote',
    'Merchant log in' => 'https://app.impeccabyte.com',
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
