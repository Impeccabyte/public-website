<?php

/**
 * @file
 * First pages: Home, Pricing and About.
 *
 * The copy is the approved wireframe copy with three changes Ry set on
 * 2026-11-18: no funding times beyond "cutoffs vary by processor", no
 * business banking, and prices kept in fields that hide while "Show prices"
 * is off. Bracketed placeholders stay as placeholders.
 *
 * Safe to re-run: a page that already exists at its address is left alone.
 * Run it with
 *   ./scripts/drush php:script scripts/content/20-pages.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$industry = fn(string $name) => ['target_id' => ib_term_id('industry', $name)];
$setup = fn(string $name, ?string $note, ?string $price_note = NULL) => ['_type' => 'setup_line', 'title' => $name, 'note' => $note, 'price_note' => $price_note];
$chip = fn(string $term, string $title, string $body, string $link, string $path, array $lines) => ['_type' => 'chip_item', 'industry' => $industry($term), 'title' => $title, 'body' => $body, 'link' => ib_link($path, $link), 'items' => $lines];
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];

// Home.
$home = ib_page('landing_page', 'Home', '/home', [
  ['hero', [
    'hero_variant' => 'split',
    'eyebrow' => 'Austin merchant services',
    'heading' => 'Getting paid should feel impeccably easy.',
    'accent_word' => 'impeccably',
    'intro' => "We're a local Austin team that sets up card processing, POS hardware and online payments for small businesses and first-time founders. Clear pricing, real people, and gear that just works.",
    'link' => ib_link('/get-a-quote', 'Get a quote'),
    'link_secondary' => ib_link('/pricing', 'See pricing'),
    'checklist' => ['Free statement review', 'Every fee in writing', 'Support from Austin'],
  ]],
  ['logo_strip', [
    // The Maverick line is kept for the footer disclosure. The logos are
    // attached by scripts/content/25-partner-logos.php; the names are the
    // fallback when a strip has no logos.
    'intro' => 'Hardware and partners we set up:',
    'names' => ['Clover', 'PAX', 'Dejavoo', 'Valor', 'Ingenico'],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Start with the job',
    'heading' => 'What do you need to do?',
    'intro' => "Pick what matters today. We'll put together the hardware, software and pricing that fits, and you can add more as you grow.",
    'items' => [
      ['_type' => 'feature_card', 'icon' => 'store', 'title' => 'Accept payments in person', 'body' => 'Countertop terminals and full POS systems that take tap, chip and swipe.', 'link' => ib_link('/solutions/in-person', 'Explore in-person')],
      ['_type' => 'feature_card', 'icon' => 'globe', 'title' => 'Sell online', 'body' => 'A payment gateway, hosted checkout and payment links that drop into your website or texts.', 'link' => ib_link('/solutions/online', 'Explore online')],
      ['_type' => 'feature_card', 'icon' => 'smartphone', 'title' => 'Get paid on the go', 'body' => 'Pocket-size readers and tap to pay on your phone for markets, pop-ups and field work.', 'link' => ib_link('/solutions/on-the-go', 'Explore mobile')],
      ['_type' => 'feature_card', 'icon' => 'repeat', 'title' => 'Bill customers on a schedule', 'body' => "Recurring billing and invoices that update expired cards for you, so payments don't quietly stop.", 'link' => ib_link('/solutions/recurring-billing', 'Explore recurring billing')],
      ['_type' => 'feature_card', 'icon' => 'circle-percent', 'title' => 'Lower your processing costs', 'body' => "Dual pricing, interchange plus or a simple flat rate. We'll show you the math on your real statement.", 'link' => ib_link('/pricing', 'Compare pricing programs'), 'highlight' => 1],
      ['_type' => 'feature_card', 'icon' => 'zap', 'title' => 'Know when your money arrives', 'body' => 'We set up your deposits and show you how batching works. Funding cutoffs vary by processor.', 'link' => ib_link('/solutions/funding', 'See how funding works')],
    ],
  ]],
  ['chip_links', [
    'eyebrow' => 'Industries',
    'heading' => 'Built for the way Austin does business',
    'items' => [
      $chip('Restaurants & Bars', 'Faster tables, cleaner tips, smaller fees', 'Pay at the table, split checks in seconds and adjust tips before close. Dual pricing keeps card costs off your margins.', 'See restaurant solutions', '/industries/restaurants', [
        $setup('Clover Station Duo at the counter', NULL, 'From $135/mo or $2,739 to own'),
        $setup('Clover Flex for tableside pay', NULL, 'From $50/mo per handheld'),
        $setup('Cash Price', 'Card customers cover the processing cost'),
      ]),
      $chip('Food Trucks', 'Take cards anywhere you park', 'A cellular terminal that keeps working when the Wi-Fi does not, with deposits set up before your first shift.', 'See food truck setups', '/industries/food-trucks', [
        $setup('Clover Flex with cellular data', 'Works off LTE', 'From $50/mo, works off LTE'),
        $setup('Cash Price', 'Keep more of every taco'),
        $setup('Funding', 'Cutoffs vary by processor'),
      ]),
      $chip('Retail', 'A counter that keeps up with Saturday', 'Barcode scanning, inventory and fast tap payments, with a setup that grows from one register to three.', 'See retail setups', '/industries/retail', [
        $setup('Clover Mini at checkout', NULL, 'From $60/mo or $1,189 to own'),
        $setup('Barcode scanner', NULL, 'From $10/mo'),
        $setup('Pro', 'Best for higher monthly volume'),
      ]),
      $chip('Salons & Spas', 'Book, check out and tip in one tap', 'Card-on-file for no-shows, easy tips for stylists and a small countertop device that fits a crowded station.', 'See salon setups', '/industries/salons-and-spas', [
        $setup('Clover Compact at the front desk', NULL, 'From $20/mo or $399 to own'),
        $setup('Recurring billing for memberships', 'Card account updater'),
        $setup('Simple', 'One rate, easy to budget'),
      ]),
      $chip('Home Services', 'Get paid before you leave the driveway', 'Invoices by text, a reader in your pocket and payments that sync with your scheduling and accounting tools.', 'See field service setups', '/industries/home-services', [
        $setup('Clover Go mobile reader', NULL, 'From $10/mo or $209 to own'),
        $setup('Payment links and invoices', 'Send by text or email'),
        $setup('Integrations', 'Connect your accounting software'),
      ]),
      $chip('Healthcare & Dental', 'Payments that respect the front desk', 'Card-on-file, payment plans and text-to-pay for balances, with secure storage handled by the gateway.', 'See healthcare setups', '/industries/healthcare', [
        $setup('Countertop terminal', 'Dejavoo Z8', 'Dejavoo Z8 from $20/mo'),
        $setup('Text-to-pay', 'Collect balances after the visit'),
        $setup('Recurring payment plans', 'Automatic monthly charges'),
      ]),
      $chip('Auto Repair', 'Close out big tickets with confidence', 'Higher-ticket payments with fraud tools, plus text-to-pay so customers can approve and pay before pickup.', 'See auto setups', '/industries/auto-repair', [
        $setup('Valor VP550 terminal', NULL, 'From $30/mo or $599 to own'),
        $setup('Cash Price', 'Offset fees on large repairs'),
        $setup('Chargeback protection', 'Alerts before disputes escalate'),
      ]),
      $chip('Professional Services', 'Invoices that pay themselves', 'Send invoices, take ACH or card, and keep retainers on autopay without chasing anybody.', 'See professional services', '/industries/professional-services', [
        $setup('Online gateway', 'Virtual terminal and invoicing'),
        $setup('ACH payments', 'Lower cost for large invoices'),
        $setup('Recurring billing', 'Retainers on a schedule'),
      ]),
      $chip('Nonprofits', 'More of every gift goes to the mission', 'Online donation pages, event-day card readers and recurring giving for monthly supporters.', 'See nonprofit setups', '/industries/nonprofits', [
        $setup('Hosted donation page', 'Drops into your website'),
        $setup('Clover Go for events', NULL, 'From $10/mo'),
        $setup('Recurring giving', 'Monthly donors on autopay'),
      ]),
      $chip('Pop-Ups & Markets', 'Set up in five minutes, sell all weekend', 'A reader that pairs with your phone, tap to pay as a backup, and no long setup when you are new.', 'See pop-up setups', '/industries/pop-ups-and-markets', [
        $setup('SwipeSimple B350 reader', NULL, 'From $10/mo or $199 to own'),
        $setup('Tap to pay on your phone', 'No extra hardware needed'),
        $setup('Simple', 'Easy while you are starting out'),
      ]),
    ],
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Hardware that fits your counter, cart or kitchen',
    'intro' => 'Buy it outright or lease it month to month. Every device ships programmed and ready to take its first payment.',
    'link' => ib_link('/hardware', 'Browse all hardware'),
    'source' => 'featured',
    'price_note' => 'Prices before tax. Lease is month to month. Clover devices include a starter kit and 3-year protection.',
  ]],
  ['stats', [
    'items' => [
      ['_type' => 'stat', 'value' => '40+', 'label' => 'Terminals, readers and POS systems we can set up'],
      ['_type' => 'stat', 'value' => '1 business day', 'label' => 'How fast a local specialist replies to your question'],
      ['_type' => 'stat', 'value' => 'Free', 'label' => 'A line-by-line review of your current statement'],
      ['_type' => 'stat', 'value' => '$10/mo', 'label' => 'Starting lease for a mobile card reader', 'is_price' => 1],
    ],
  ]],
  ['testimonial', [
    'quote' => 'We switched to dual pricing and our monthly processing bill went from a line item I dreaded to one I barely notice. And when I had questions, someone in Austin actually picked up.',
    'name' => '[Merchant name], owner',
    'role' => '[Business name] · East Austin coffee shop',
    'label' => 'Their monthly fees',
    'before' => '$[X,XXX]',
    'after' => '$[XXX]',
    'link' => ib_link('/industries/restaurants', 'Read their story'),
    'placeholder' => 1,
  ]],
  ['faq', [
    'eyebrow' => 'Questions',
    'heading' => 'Good questions, straight answers',
    'intro_html' => ib_html('<p>Don\'t see yours? <a href="/get-a-quote">Ask a local specialist</a> and we\'ll reply within one business day.</p>'),
    'layout' => 'split',
    'items' => [
      $faq('Is Impeccabyte a payment processor?', 'We are an authorized sales agent of Maverick Payments. Maverick and its sponsor banks provide your merchant account and process your transactions; we set you up, choose the right gear and stay your local point of contact.'),
      $faq('How fast will I get my money?', 'Deposit timing depends on your account and on when you close your batch each day. Funding cutoffs vary by processor, and we walk you through yours when we set you up.'),
      $faq('What is dual pricing, and can I use it in Texas?', 'Dual pricing shows a cash price and a card price. Customers who pay by card cover the cost of processing. It is widely used in Texas, and we set up the signage and receipts that card-brand rules require.'),
      $faq('Do I have to buy hardware?', 'No. You can buy a device outright or lease it month to month. If you already own compatible equipment, we can often reprogram it for you.'),
      $faq('I am just starting out. Can I still apply?', 'Yes. New businesses and first-time founders are welcome to apply. Have your EIN or SSN, a business bank account and a photo ID ready, and we will walk you through the rest.'),
    ],
  ]],
  ['cta_band', [
    'heading' => "Send us your statement. We'll show you what you could save.",
    'accent_word' => 'could',
    'body' => 'A free, line-by-line review from someone local. No pressure, no obligation.',
    'link' => ib_link('/pricing#statement', 'Upload a statement'),
    'link_secondary' => ib_link('/get-a-quote', 'Get a quote'),
  ]],
]);

// Home is the front page. A fresh install has no nodes, so this is set here
// once Home exists rather than in exported config alone.
$site = \Drupal::configFactory()->getEditable('system.site');
if ($site->get('page.front') !== '/node/' . $home->id()) {
  $site->set('page.front', '/node/' . $home->id())->save();
}

// Pricing.
$row = fn(string $label, array $cells) => ['_type' => 'table_row', 'label' => $label, 'cells' => $cells];
// Rows holding prices hide behind "Pricing coming soon" while "Show prices" is off.
$price_row = fn(string $label, array $cells) => ['_type' => 'table_row', 'label' => $label, 'cells' => $cells, 'is_price' => 1];
ib_page('landing_page', 'Pricing', '/pricing', [
  ['hero', [
    'hero_variant' => 'centered',
    'eyebrow' => 'Pricing',
    'heading' => 'Pricing in plain English.',
    'accent_word' => 'plain',
    'intro' => "Three ways to pay for card processing. We'll tell you which one saves you the most, using your real statement, and put every fee in writing.",
  ]],
  ['pricing_cards', [
    'heading' => 'Pricing programs',
    'items' => [
      ['_type' => 'pricing_card', 'title' => 'Simple', 'body' => "A flat rate: one rate on every card. Easy to budget while you're getting started.", 'value' => '2.59% + 10¢', 'unit' => 'per in-person card sale', 'bullets' => ['Best for new businesses', 'Same cost for every card type'], 'link' => ib_link('/get-a-quote', 'Get a Simple quote')],
      ['_type' => 'pricing_card', 'title' => 'Cash Price', 'badge' => 'Lowest fees per sale', 'body' => 'Dual pricing: a cash price and a card price. Card customers cover processing.', 'value' => '$14.95/mo', 'unit' => 'program fee, gateway included', 'bullets' => ['Best for restaurants, retail and services', 'Signage and receipt setup included'], 'link' => ib_link('/solutions/dual-pricing', 'See how dual pricing works'), 'featured' => 1],
      ['_type' => 'pricing_card', 'title' => 'Pro', 'body' => "Interchange plus: the card network's actual cost plus one markup, in writing.", 'value' => 'Cost + 0.35%', 'unit' => '+ 10¢ per in-person card sale', 'bullets' => ['Best for steady, higher monthly volume', 'Most transparent statement'], 'link' => ib_link('/get-a-quote', 'Get a Pro quote')],
    ],
    'footnote' => 'Gateway fees depend on your setup and are listed on every quote.',
    'price_note' => 'Hardware from $10/mo leased or $199 to own. Keyed and online rates, and any gateway your setup needs, are listed on every quote.',
  ]],
  ['comparison_table', [
    'eyebrow' => 'Side by side',
    'heading' => 'How the three programs compare',
    'columns' => ['Simple', 'Cash Price', 'Pro'],
    'highlight_column' => 2,
    'items' => [
      $row('How it works', ['One percentage plus a few cents on every card sale.', 'Two prices at checkout. The card price covers the processing cost.', "The network's real cost per card, plus one fixed markup."]),
      $price_row('Rate', ['2.59% + 10¢ in person. 3.29% + 30¢ keyed or online. American Express included.', 'The card price is the cash price plus 4%. Cash customers pay the posted cash price.', 'Card-network cost + 0.35% + 10¢ in person, or + 0.50% + 20¢ keyed or online. Lower markups from $75,000 a month.']),
      $price_row('Monthly fee', ['$9.95 for your account and PCI compliance', '$14.95 program fee, with the gateway, dashboard and PCI compliance included', '$19.90 for your account, reporting and PCI compliance']),
      $row('Who covers the processing cost', ['You', 'Customers who pay by card', 'You']),
      $row('Monthly predictability', ['High. Same rate every time.', 'High. One fixed monthly fee.', 'Medium. Varies with card mix.']),
      $row('Usually cheapest when', ['You process under about $15,000 a month', 'Your customers are comfortable seeing two prices', 'You process $15,000 or more a month']),
      $row('Setup needed', ['None', 'Signage and receipt setup, which we handle', 'None']),
      $row('Statement clarity', ['Simple', 'Simple', 'Detailed, line by line']),
      $price_row('Chargeback fee', ['$25', '$25', '$25']),
      $price_row('Account setup, batch, statement or annual fees', ['None', 'None', 'None']),
    ],
  ]],
  ['comparison_table', [
    'eyebrow' => 'Other fees',
    'heading' => 'Every other fee, in one place',
    'intro' => 'These apply on any program, only when you use the service. Your quote lists the ones that fit your setup.',
    'columns' => ['What it costs'],
    'items' => [
      $price_row('Chargeback', ['$25 each']),
      $price_row('Retrieval request', ['$15 each']),
      $price_row('Voice authorization', ['95¢ each']),
      $price_row('Address verification', ['5¢ each on Pro. Included on Simple and Cash Price.']),
      $price_row('ACH (eCheck) payments', ['35¢ each, plus $9.95/mo']),
      $price_row('ACH returns', ['$5 each, or $25 for an unauthorized return']),
      $price_row('Card account updater', ['$39.95/mo plus 50¢ per updated card, with a $187.95 setup fee']),
      $price_row('3D Secure for online payments', ['30¢ each']),
    ],
    'footnote' => 'Clover devices add their own monthly fees, listed on each device page.',
  ]],
  ['webform_embed', [
    'eyebrow' => 'Free statement review',
    'heading' => "Send us your statement. We'll do the math.",
    'intro' => "Upload one recent processing statement and a local specialist will show you, line by line, what you pay now and what you'd pay with us.",
    'items' => [
      ['_type' => 'step', 'title' => 'Upload a statement', 'body' => 'PDF or photo, any processor'],
      ['_type' => 'step', 'title' => 'We review it', 'body' => 'Usually within one business day'],
      ['_type' => 'step', 'title' => 'You get a written comparison', 'body' => 'No obligation to switch'],
    ],
    'note' => 'Private upload. We never share your statement.',
    'webform' => ['target_id' => 'statement_review'],
    'anchor' => 'statement',
  ]],
  ['faq', [
    'heading' => 'Pricing questions',
    'layout' => 'centered',
    'items' => [
      $faq('Are there monthly fees?', 'Yes, one for each program. It covers your account and PCI compliance, and on Cash Price the gateway too. Clover devices and online gateways add their own monthly fees. Every one is listed on your quote before you sign.'),
      $faq('Is there a contract or cancellation fee?', 'The contract term and any cancellation fee are spelled out in your formal quote, before you sign. They are not published online. Ask us anything before you sign, and we will answer in plain English.'),
      $faq('Why do you need my statement?', 'Your statement shows your real card mix and volume, which is the only honest way to compare programs. Without it, any savings number is a guess.'),
      $faq('Can I change programs later?', 'Yes. If your business changes, we will review your numbers and move you to the program that fits.'),
      $faq('Do these prices apply to specialty industries?', 'Not always. Businesses such as CBD sellers, online pharmacies or firearms dealers cost more for the sponsor banks to process, so we quote their rates one by one. Your quote shows every rate and fee before you sign.'),
    ],
  ]],
]);

// About, a short page so the Company links resolve until real copy arrives.
ib_page('landing_page', 'About Us', '/about', [
  ['hero', [
    'hero_variant' => 'centered',
    'eyebrow' => 'Company',
    'heading' => 'A local team for Austin businesses.',
    'accent_word' => 'local',
    'intro' => 'Impeccabyte is an Austin, Texas merchant services agency and an authorized sales agent of Maverick Payments. We set up card processing, hardware and online payments, and we stay your point of contact after you open.',
    'link' => ib_link('/get-a-quote', 'Get a quote'),
  ]],
  ['callout', [
    'callout_variant' => 'info',
    'title' => 'More about us is on the way',
    'text' => ib_html('<p>[Company story, team and office details to be supplied.]</p>'),
  ]],
  ['cta_band', [
    'heading' => 'Talk to someone local.',
    'accent_word' => 'local',
    'body' => 'Tell us how you take payments today and we will reply within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a quote'),
  ]],
]);

print "Pages are in place.\n";
