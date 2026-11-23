<?php

/**
 * @file
 * Solutions and industry pages: the dual pricing and restaurants pages from
 * the wireframes, and short pages for the rest so every menu link resolves.
 *
 * Copy follows the wireframes with the changes Ry set on 2026-11-18: no
 * funding times beyond "cutoffs vary by processor" and no business banking.
 * The estimator's rates are the wireframe's example values and still need
 * confirming.
 *
 * Safe to re-run: a page that already exists at its address is left alone.
 * Run it with
 *   ./scripts/drush php:script scripts/content/40-solutions-industries.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$term = fn(string $vid, string $name) => ['target_id' => ib_term_id($vid, $name)];
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$device = fn(string $slug) => ($node = ib_node_by_alias('/hardware/' . $slug)) ? ['target_id' => $node->id()] : NULL;

// Devices the dual pricing wireframe lists as ready, less PAX: Maverick's
// knowledge base lists PAX terminals for surcharging only, not dual pricing.
foreach (['dejavoo-z8', 'dejavoo-qd4', 'valor-vp550'] as $slug) {
  $node = ib_node_by_alias('/hardware/' . $slug);
  if ($node && !$node->get('field_dual_pricing_ready')->value) {
    $node->set('field_dual_pricing_ready', 1)->save();
  }
}

// Dual pricing.
$dual = ib_page('solution', 'Dual Pricing', '/solutions/dual-pricing', [
  ['hero', [
    'hero_variant' => 'split',
    'hero_art' => 'receipt',
    'eyebrow' => 'Dual pricing',
    'heading' => 'Keep the whole sale, not most of it.',
    'accent_word' => 'whole',
    'intro' => 'Dual pricing shows customers a cash price and a card price at checkout. Card customers cover the cost of processing, so the fee on most sales comes off your bill.',
    'link' => ib_link('/get-a-quote', 'Get a dual pricing quote'),
    'link_secondary' => ib_link('/solutions/dual-pricing#savings', 'Estimate my savings'),
    'show_breadcrumb' => 1,
  ]],
  ['steps', [
    'eyebrow' => 'How it works',
    'heading' => 'Four steps, and we handle most of them',
    'items' => [
      $step('We set up both prices', 'We program your terminal to show a cash price and a card price on every sale. No repricing your menu by hand.'),
      $step('Customers see it up front', 'Signage at the door and register, plus a clear receipt, show both prices before anyone pays.'),
      $step('The card price covers processing', 'The small difference offsets what the card networks charge, so it stops coming out of your margin.'),
      $step('We review your first month', 'After your first statement we sit down together and check the savings, line by line.'),
    ],
  ]],
  ['savings_estimator', [
    'eyebrow' => 'Savings comparison',
    'heading' => 'See what a month could look like',
    'intro' => 'Move the slider to your typical monthly card sales. The estimate updates for each pricing program.',
    'assumptions' => 'Example only. Assumes Simple at 2.59% + 10¢ in person, Pro at a typical 1.80% + 10¢ card-network cost plus our 0.35% + 10¢ markup, a $25 average ticket and the $14.95 monthly Cash Price program fee. Your real numbers come from your statement.',
    'flat_percent' => 2.59,
    'flat_cents' => 10,
    'ic_percent' => 2.15,
    'ic_cents' => 20,
    'avg_ticket' => 25,
    'dp_fee' => 14.95,
    'default_volume' => 30000,
    'anchor' => 'savings',
  ]],
  ['card_grid', [
    'heading' => 'Devices that are dual pricing ready',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'dual_pricing',
  ]],
  ['callout', [
    'callout_variant' => 'warning',
    'title' => 'Staying compliant is part of the setup',
    'text' => ib_html("<p>We provide door and register signage, set up receipts that show both prices, and keep an eye on card-brand rule changes for you. Dual pricing is not the same as surcharging, which has separate rules and caps. If you are unsure which fits your business, we'll walk you through it before anything changes at your counter.</p>"),
  ]],
  ['faq', [
    'heading' => 'Dual pricing questions',
    'layout' => 'centered',
    'items' => [
      $faq('Will my customers be upset?', 'Most customers already see cash and card prices at gas stations, so it rarely surprises anyone. Clear signage does the explaining, and cash stays an option for anyone who wants the lower price.'),
      $faq('Does it apply to debit cards too?', 'Yes. Under dual pricing the card price applies to every card, credit or debit. That keeps it simple at the register and consistent with card-brand rules.'),
      $faq('What does it cost me each month?', 'Dual pricing has one monthly program fee that covers the gateway, your dashboard and PCI compliance, plus any hardware you lease. Your quote lists every line so there are no surprises.'),
      $faq('Can I switch back later?', 'Yes. If dual pricing is not right for your business, we can move you to Pro or Simple. We will review the numbers with you first.'),
      $faq('Do I need new hardware?', 'Not always. Many Dejavoo and Valor terminals can be reprogrammed for dual pricing. If yours cannot, we will recommend a device that fits your counter and budget.'),
    ],
  ]],
  // The three resources are attached by scripts/content/50-resources.php.
  ['related_resources', ['heading' => 'Keep reading']],
  ['cta_band', [
    'heading' => 'Ready to stop paying to get paid?',
    'link' => ib_link('/get-a-quote', 'Get a dual pricing quote'),
  ]],
], [
  'field_category' => $term('solution_category', 'Lower Your Costs'),
  'field_short_description' => 'Show a cash and card price and keep more of each sale',
  'field_icon' => 'circle-percent',
]);

// The other solutions: a short page each, from the menu's own descriptions.
// In person has its full page in scripts/content/45-in-person.php.
$solutions = [
  ['Online', 'online', 'Accept Payments', 'globe', 'Gateways, hosted checkout and payment links', 'Sell online without rebuilding your site.', 'online', 'A payment gateway, hosted checkout and payment links that drop into your website or texts.'],
  ['On the Go', 'on-the-go', 'Accept Payments', 'smartphone', 'Mobile readers and tap to pay on your phone', 'Get paid wherever the work is.', 'wherever', 'Pocket-size readers and tap to pay on your phone for markets, pop-ups and field work.'],
  ['Recurring Billing', 'recurring-billing', 'Accept Payments', 'repeat', 'Subscriptions, invoices and card updater', 'Bill customers on a schedule.', 'schedule', "Recurring billing and invoices that update expired cards for you, so payments don't quietly stop."],
  ['Interchange Plus', 'interchange-plus', 'Lower Your Costs', 'chart-line', 'Pass-through pricing with one clear markup', 'Pay the real cost, plus one clear markup.', 'clear', "You pay the card network's actual cost, plus one small markup we show in writing."],
  ['Chargeback Protection', 'chargeback-protection', 'Lower Your Costs', 'shield-check', 'Alerts and dispute help before losses stack up', 'Catch disputes before they cost you.', 'before', 'Alerts and dispute help before losses stack up.'],
  ['Integrations', 'integrations', 'Run Your Business', 'plug', 'Connect invoicing, accounting and your POS', 'Payments that talk to your other tools.', 'talk', 'Connect invoicing, accounting and your POS.'],
  ['Funding', 'funding', 'Run Your Business', 'zap', 'How and when deposits reach your bank', 'Know when your money arrives.', 'when', 'We set up your deposits and show you how batching works. Funding cutoffs vary by processor.'],
];
foreach ($solutions as [$title, $slug, $category, $icon, $short, $headline, $accent, $lead]) {
  ib_page('solution', $title, '/solutions/' . $slug, [
    ['hero', ['hero_variant' => 'centered', 'eyebrow' => $title, 'heading' => $headline, 'accent_word' => $accent, 'intro' => $lead, 'link' => ib_link('/get-a-quote', 'Get a quote'), 'link_secondary' => ib_link('/pricing', 'See pricing'), 'show_breadcrumb' => 1]],
    ['callout', ['callout_variant' => 'info', 'title' => 'More detail is on the way', 'text' => ib_html('<p>[Full page copy for ' . strtolower($title) . ' to be supplied.] In the meantime, a local specialist can answer your questions.</p>')]],
    ['cta_band', ['heading' => 'Talk to someone local.', 'accent_word' => 'local', 'body' => 'Tell us how you take payments today and we will reply within one business day.', 'link' => ib_link('/get-a-quote', 'Get a quote')]],
  ], [
    'field_category' => $term('solution_category', $category),
    'field_short_description' => $short,
    'field_icon' => $icon,
  ]);
}

// Restaurants and bars.
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
ib_page('industry_page', 'Restaurants & Bars', '/industries/restaurants', [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Restaurants & Bars',
    'heading' => 'Faster tables, cleaner tips, smaller fees.',
    'accent_word' => 'smaller',
    'intro' => 'From taco trucks to sit-down dining rooms, we set up payments that keep the line moving and the margins intact. One local team for your hardware, pricing and support.',
    'link' => ib_link('/get-a-quote', 'Get a restaurant quote'),
    'link_secondary' => ib_link('/industries/restaurants#setup', 'See a sample setup'),
    'media_label' => 'Photo placeholder: a server taking payment tableside with a handheld terminal',
  ]],
  ['feature_grid', [
    'eyebrow' => 'Sound familiar?',
    'heading' => 'The four things restaurant owners tell us first',
    'items' => [
      $card('circle-percent', '“Fees eat my margin.”', 'Dual pricing moves the processing cost to card customers, with clear signage that keeps it friendly.'),
      $card('users', '“The line backs up at the register.”', 'Handhelds take payment at the table and a self-order kiosk handles the lunch rush.'),
      $card('clock', '“Tips take forever at close.”', 'Adjust tips on the device before you close your batch. Funding cutoffs vary by processor.'),
      $card('receipt', '“Paper tickets get lost.”', 'A kitchen display sends orders straight to the line, in order, with timers.'),
    ],
  ]],
  ['bundle', [
    'eyebrow' => 'Recommended setup',
    'heading' => 'A setup for a 60-seat dining room',
    'intro' => 'A starting point we tune to your floor plan, menu and volume. Swap any piece for something that fits better.',
    'anchor' => 'setup',
    'items' => [
      ['_type' => 'bundle_item', 'label' => 'Front counter', 'reference' => $device('clover-station-duo'), 'note' => 'Dual screens so guests can see their order and tip', 'quantity' => 1],
      ['_type' => 'bundle_item', 'label' => 'Tableside', 'reference' => $device('clover-flex'), 'note' => 'Pay at the table, split checks, print receipts', 'quantity' => 2],
      ['_type' => 'bundle_item', 'label' => 'Kitchen', 'title' => 'Clover KDS 14-inch', 'note' => 'Orders fire to the line with timers', 'quantity' => 1, 'monthly_price' => 40, 'buy_price' => 839],
      ['_type' => 'bundle_item', 'label' => 'Pricing', 'reference' => ['target_id' => $dual->id()], 'note' => 'Card customers cover processing on each check', 'quantity' => 1],
    ],
    'addons' => ['chargeback protection', 'self-order kiosk'],
    'note' => 'Processing, Clover software and gateway fees are quoted separately.',
    'link' => ib_link('/get-a-quote', 'Quote this setup'),
  ]],
  ['case_study', [
    'eyebrow' => 'Customer story',
    'heading' => 'How [Restaurant name] cut close-out time and kept more of every check',
    'body' => 'A [XX]-seat [cuisine] spot on East Sixth moved from a legacy register to Clover with dual pricing. Servers now settle at the table and tips are adjusted before the batch closes.',
    'items' => [
      ['_type' => 'stat', 'value' => '[X]%', 'label' => 'Lower monthly fees'],
      ['_type' => 'stat', 'value' => '[X] min', 'label' => 'Faster close-out'],
    ],
    'link' => ib_link('/resources', 'Read the full story'),
    'placeholder' => 1,
  ]],
  ['faq', [
    'heading' => 'Restaurant questions',
    'layout' => 'centered',
    'items' => [
      $faq('When do tips get paid out?', 'Tips are part of the batch. Adjust them on the device before you close out and they are deposited with the rest of that batch. Funding cutoffs vary by processor.'),
      $faq('Can guests split a check at the table?', 'Yes. Clover Flex lets servers split by item, by seat or evenly, and take a different card for each share.'),
      $faq('What happens if the internet goes down?', 'Clover devices can keep taking payments offline for a period and send them once you reconnect. A cellular backup plan is also available.'),
      $faq('Can I keep my current POS software?', 'Sometimes. If your POS supports a semi-integrated terminal, we can connect one instead of replacing everything. Tell us what you run and we will check.'),
      $faq('Do you install and train staff?', 'Yes. For Austin restaurants we can set up on site and walk your team through a dinner-service dry run before you go live.'),
    ],
  ]],
  ['cta_band', [
    'heading' => "Let's walk your floor and build the right setup.",
    'link' => ib_link('/get-a-quote', 'Book a visit'),
  ]],
], ['field_industry' => $term('industry', 'Restaurants & Bars')]);

// The other industries: a short page each, from the Home industry picker.
$industries = [
  ['Food Trucks', 'food-trucks', 'Take cards anywhere you park.', 'anywhere', 'A cellular terminal that keeps working when the Wi-Fi does not, with deposits set up before your first shift.'],
  ['Retail', 'retail', 'A counter that keeps up with Saturday.', 'Saturday', 'Barcode scanning, inventory and fast tap payments, with a setup that grows from one register to three.'],
  ['Salons & Spas', 'salons-and-spas', 'Book, check out and tip in one tap.', 'one', 'Card-on-file for no-shows, easy tips for stylists and a small countertop device that fits a crowded station.'],
  ['Home Services', 'home-services', 'Get paid before you leave the driveway.', 'before', 'Invoices by text, a reader in your pocket and payments that sync with your scheduling and accounting tools.'],
  ['Healthcare & Dental', 'healthcare', 'Payments that respect the front desk.', 'respect', 'Card-on-file, payment plans and text-to-pay for balances, with secure storage handled by the gateway.'],
  ['Auto Repair', 'auto-repair', 'Close out big tickets with confidence.', 'confidence', 'Higher-ticket payments with fraud tools, plus text-to-pay so customers can approve and pay before pickup.'],
  ['Professional Services', 'professional-services', 'Invoices that pay themselves.', 'themselves', 'Send invoices, take ACH or card, and keep retainers on autopay without chasing anybody.'],
  ['Nonprofits', 'nonprofits', 'More of every gift goes to the mission.', 'mission', 'Online donation pages, event-day card readers and recurring giving for monthly supporters.'],
  ['Pop-Ups & Markets', 'pop-ups-and-markets', 'Set up in five minutes, sell all weekend.', 'weekend', 'A reader that pairs with your phone, tap to pay as a backup, and no long setup when you are new.'],
];
foreach ($industries as [$name, $slug, $headline, $accent, $lead]) {
  ib_page('industry_page', $name, '/industries/' . $slug, [
    ['hero', ['hero_variant' => 'centered', 'eyebrow' => $name, 'heading' => $headline, 'accent_word' => $accent, 'intro' => $lead, 'link' => ib_link('/get-a-quote', 'Get a quote'), 'link_secondary' => ib_link('/hardware', 'Browse hardware')]],
    ['callout', ['callout_variant' => 'info', 'title' => 'More detail is on the way', 'text' => ib_html('<p>[Full page copy for ' . strtolower($name) . ' to be supplied.] In the meantime, a local specialist can put together a setup for you.</p>')]],
    ['cta_band', ['heading' => 'Talk to someone local.', 'accent_word' => 'local', 'body' => 'Tell us about your business and we will reply within one business day.', 'link' => ib_link('/get-a-quote', 'Get a quote')]],
  ], ['field_industry' => $term('industry', $name)]);
}

print "Solutions and industry pages are in place.\n";
