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

// Main menu, by business line (2026-11-23, see 07-menu-business-lines.php).
// Services holds one child per business line. Payments is the only line so
// far: the three solution categories plus Hardware. A second line, such as
// travel, is another child of Services; the mega menu shows a list of lines
// only once there are two. Business banking is out of scope, so it has no
// link.
$services = ib_menu_link('main', 'Services', 'route:<nolink>', ['weight' => 0]);
$payments = ib_menu_link('main', 'Payments', 'route:<nolink>', ['parent' => $services, 'weight' => 0]);
$accept = ib_menu_link('main', 'Accept Payments', 'route:<nolink>', ['parent' => $payments, 'weight' => 0]);
ib_menu_link('main', 'In Person', 'internal:/solutions/in-person', ['parent' => $accept, 'weight' => 0, 'icon' => 'store', 'description' => 'Countertop terminals and full POS systems']);
ib_menu_link('main', 'Online', 'internal:/solutions/online', ['parent' => $accept, 'weight' => 1, 'icon' => 'globe', 'description' => 'Gateways, hosted checkout and payment links']);
ib_menu_link('main', 'On the Go', 'internal:/solutions/on-the-go', ['parent' => $accept, 'weight' => 2, 'icon' => 'smartphone', 'description' => 'Mobile readers and tap to pay on your phone']);
ib_menu_link('main', 'Recurring Billing', 'internal:/solutions/recurring-billing', ['parent' => $accept, 'weight' => 3, 'icon' => 'repeat', 'description' => 'Subscriptions, invoices and card updater']);
$lower = ib_menu_link('main', 'Lower Your Costs', 'route:<nolink>', ['parent' => $payments, 'weight' => 1]);
ib_menu_link('main', 'Dual Pricing', 'internal:/solutions/dual-pricing', ['parent' => $lower, 'weight' => 0, 'icon' => 'circle-percent', 'description' => 'Show a cash and card price and keep more of each sale']);
ib_menu_link('main', 'Interchange Plus', 'internal:/solutions/interchange-plus', ['parent' => $lower, 'weight' => 1, 'icon' => 'chart-line', 'description' => 'Pass-through pricing with one clear markup']);
ib_menu_link('main', 'Chargeback Protection', 'internal:/solutions/chargeback-protection', ['parent' => $lower, 'weight' => 2, 'icon' => 'shield-check', 'description' => 'Alerts and dispute help before losses stack up']);
$run = ib_menu_link('main', 'Run Your Business', 'route:<nolink>', ['parent' => $payments, 'weight' => 2]);
ib_menu_link('main', 'Integrations', 'internal:/solutions/integrations', ['parent' => $run, 'weight' => 0, 'icon' => 'plug', 'description' => 'Connect invoicing, accounting and your POS']);
ib_menu_link('main', 'Funding', 'internal:/solutions/funding', ['parent' => $run, 'weight' => 1, 'icon' => 'zap', 'description' => 'How and when deposits reach your bank']);

$hardware = ib_menu_link('main', 'Hardware', 'route:<nolink>', ['parent' => $payments, 'weight' => 3]);
// Travel, the second line (2026-11-23): for businesses with an active
// Impeccabyte merchant account, booked through their account manager in the
// merchant app. Its pages are built by 52-travel.php.
$travel = ib_menu_link('main', 'Travel', 'route:<nolink>', ['parent' => $services, 'weight' => 1]);
$trip = ib_menu_link('main', 'Plan a Trip', 'route:<nolink>', ['parent' => $travel, 'weight' => 0]);
ib_menu_link('main', 'Trip Planning', 'internal:/travel', ['parent' => $trip, 'weight' => 0, 'icon' => 'map-pin', 'description' => 'How we plan and what it costs']);
ib_menu_link('main', 'Hotel Perks', 'internal:/travel/hotel-perks', ['parent' => $trip, 'weight' => 1, 'icon' => 'bed', 'description' => 'What partner hotels usually include']);
$book = ib_menu_link('main', 'Book a Trip', 'route:<nolink>', ['parent' => $travel, 'weight' => 1]);
ib_menu_link('main', 'Log In', 'https://app.impeccabyte.com/login', ['parent' => $book, 'weight' => 0, 'icon' => 'lock', 'description' => 'Your account manager books your trip']);
// Hardware is the fourth column under Payments, so its links are titles only
// to keep the column as short as the others.
ib_menu_link('main', 'All Devices', 'internal:/hardware', ['parent' => $hardware, 'weight' => 0, 'icon' => 'credit-card']);
$device_types = [
  'Countertop Terminals' => ['Countertop Terminal', 'store'],
  'Handheld Terminals' => ['Handheld', 'smartphone'],
  'POS Systems' => ['POS System', 'calculator'],
  'Mobile Readers' => ['Mobile Reader', 'nfc'],
  'Self-Order Kiosks' => ['Self-Order Kiosk', 'users'],
];
$weight = 1;
foreach ($device_types as $title => [$term, $icon]) {
  ib_menu_link('main', $title, $term_filter('/hardware', 'type', 'device_type', $term), ['parent' => $hardware, 'weight' => $weight++, 'icon' => $icon]);
}

$industries = ib_menu_link('main', 'Who We Serve', 'route:<nolink>', ['weight' => 1]);
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

ib_menu_link('main', 'Pricing', 'internal:/pricing', ['weight' => 2]);

$resources = ib_menu_link('main', 'Resources', 'internal:/resources', ['weight' => 3]);
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

$company = ib_menu_link('main', 'Company', 'route:<nolink>', ['weight' => 4]);
ib_menu_link('main', 'About Us', 'internal:/about', ['parent' => $company, 'weight' => 0, 'icon' => 'users', 'description' => 'A local Austin team']);
// Opens the quote form, so it says so. The header's "Log in" link covers the
// merchant app.
ib_menu_link('main', 'Get a Quote', 'internal:/get-a-quote', ['parent' => $company, 'weight' => 1, 'icon' => 'message-circle', 'description' => 'Tell us about your business']);

// Footer columns.
$footer = [
  // Under the Payments column the links use the main menu's names.
  'footer-solutions' => [
    'In Person' => 'internal:/solutions/in-person',
    'Online' => 'internal:/solutions/online',
    'On the Go' => 'internal:/solutions/on-the-go',
    'Dual Pricing' => 'internal:/solutions/dual-pricing',
    'Chargeback Protection' => 'internal:/solutions/chargeback-protection',
  ],
  'footer-hardware' => [
    'All Devices' => 'internal:/hardware',
    'Countertop Terminals' => $term_filter('/hardware', 'type', 'device_type', 'Countertop Terminal'),
    'Handheld Terminals' => $term_filter('/hardware', 'type', 'device_type', 'Handheld'),
    'POS Systems' => $term_filter('/hardware', 'type', 'device_type', 'POS System'),
    'Mobile Readers' => $term_filter('/hardware', 'type', 'device_type', 'Mobile Reader'),
    'Self-Order Kiosks' => $term_filter('/hardware', 'type', 'device_type', 'Self-Order Kiosk'),
  ],
  'footer-travel' => [
    'Trip Planning' => 'internal:/travel',
    'Hotel Perks' => 'internal:/travel/hotel-perks',
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
    // Opens the quote form, so it says so. The header's "Log in" covers the
    // merchant app.
    'Get a Quote' => 'internal:/get-a-quote',
  ],
  'footer-legal' => [
    'Privacy' => 'internal:/privacy',
    'Terms' => 'internal:/terms',
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
