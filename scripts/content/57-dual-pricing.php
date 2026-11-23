<?php

/**
 * @file
 * The revised Dual pricing page, as designed and approved on 2026-11-19.
 *
 * scripts/content/40-solutions-industries.php creates the page from the
 * wireframe; this script upgrades it. It adds "What your customers see",
 * "Where dual pricing works best" and a dual pricing and surcharging table,
 * and updates the hero, device cards, callout, questions and closing band.
 * The savings estimator and the "Keep reading" row are kept as they are.
 *
 * Maverick offers both dual pricing and surcharging and says both work
 * online (maverickpayments.com/surcharging). The surcharging column states
 * general card network rules, not legal advice. No prices.
 *
 * Safe to re-run: only a page still in its wireframe shape is upgraded.
 * Run it with
 *   ./scripts/drush php:script scripts/content/57-dual-pricing.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/solutions/dual-pricing';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$row = fn(string $label, array $cells) => ['_type' => 'table_row', 'label' => $label, 'cells' => $cells];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No dual pricing page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles !== ['hero', 'steps', 'savings_estimator', 'card_grid', 'callout', 'faq', 'related_resources', 'cta_band']) {
  print "Dual pricing page already revised or edited; left as it is.\n";
  return;
}
// Kept as they are.
$estimator = $existing[2];
$related = $existing[6];

$sections = [
  ib_paragraph('hero', [
    'hero_variant' => 'split',
    'hero_art' => 'receipt',
    'eyebrow' => 'Dual pricing',
    'heading' => 'Keep the whole sale, not most of it.',
    'accent_word' => 'whole',
    'intro' => 'Dual pricing shows customers a cash price and a card price at checkout. Card customers cover the cost of processing, so the fee on most sales comes off your bill.',
    'link' => ib_link('/get-a-quote', 'Get a dual pricing quote'),
    'link_secondary' => ib_link($path . '#savings', 'Estimate my savings'),
    'checklist' => ['Signage and receipts included', 'Works in store and online', 'Switch back any time'],
    'show_breadcrumb' => 1,
  ]),
  ib_paragraph('media_split', [
    'eyebrow' => 'At your counter',
    'heading' => 'What your customers see',
    'body' => 'No surprises at the register. Both prices are shown before anyone pays, the same way gas stations have done it for years.',
    'checklist' => [
      'A sign at the door and the register explains the two prices',
      'The terminal shows the cash and card total before they tap',
      'The receipt lists both prices and what they saved by paying cash',
    ],
    'media_label' => 'a customer paying at a counter beside a small cash and card price sign',
  ]),
  ib_paragraph('steps', [
    'eyebrow' => 'How it works',
    'heading' => 'Four steps, and we handle most of them',
    'items' => [
      $step('We set up both prices', 'We program your terminal to show a cash price and a card price on every sale. No repricing your menu by hand.'),
      $step('Customers see it up front', 'Signage at the door and register, plus a clear receipt, show both prices before anyone pays.'),
      $step('The card price covers processing', 'The small difference offsets what the card networks charge, so it stops coming out of your margin.'),
      $step('We review your first month', 'After your first statement we sit down together and check the savings, line by line.'),
    ],
  ]),
  ib_paragraph('feature_grid', [
    'eyebrow' => 'Is it a fit?',
    'heading' => 'Where dual pricing works best',
    'intro' => "It suits most counter businesses. If it isn't right for yours, we'll say so and show you what costs less.",
    'items' => [
      $card('receipt', 'Lots of small sales', 'Coffee shops, food trucks and quick service, where fees on hundreds of small tickets add up fast.'),
      $card('circle-percent', 'Thin margins', 'Restaurants, convenience stores and auto repair, where a few percent is the difference between a good month and a bad one.'),
      $card('users', 'Regulars you know', 'Salons, barbers and neighborhood shops, where a quick word at the counter explains the change.'),
      $card('store', 'Cash is welcome', 'Businesses happy to take cash, so customers always have a way to pay the lower price.'),
    ],
  ]),
  $estimator,
  ib_paragraph('comparison_table', [
    'eyebrow' => 'Side by side',
    'heading' => 'Dual pricing and surcharging are not the same',
    'intro' => 'Both pass the cost of card processing to card customers, but they follow different rules. We offer both and help you pick the one that fits.',
    'columns' => ['Dual pricing', 'Surcharging'],
    'highlight_column' => 1,
    'items' => [
      $row('What customers see', ['Two prices on every sale: a cash price and a card price.', 'One price, plus a fee added at checkout when paying by credit card.']),
      $row('Which cards', ['Every card, credit and debit.', 'Credit cards only. Debit cards cannot be surcharged.']),
      $row('Limits', ['Both prices must be shown before the customer pays.', 'Card networks cap the fee, and some states limit surcharging.']),
      $row('Signs and receipts', ['Signs at the door and register; both prices on the receipt.', 'Notice at the door and register; the fee shown on the receipt.']),
      $row('Online', ['Yes, with both prices shown at checkout.', 'Yes, with the fee shown before the customer pays.']),
    ],
    'footnote' => 'General information about card network rules, not legal advice. We confirm what applies to your business before anything changes at your counter.',
  ]),
  ib_paragraph('card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Devices that are dual pricing ready',
    'intro' => 'A few of the 13 devices that show both prices on screen and print them on the receipt. Already have one? We can usually reprogram it.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $alias) => ($device = ib_node_by_alias($alias)) ? ['target_id' => $device->id()] : NULL, ['/hardware/dejavoo-z8', '/hardware/dejavoo-qd4', '/hardware/valor-vp550', '/hardware/dejavoo-p3']))),
  ]),
  ib_paragraph('callout', [
    'callout_variant' => 'warning',
    'title' => 'Staying compliant is part of the setup',
    'text' => ib_html('<p>We provide door and register signage, set up receipts that show both prices, and keep an eye on card-brand rule changes for you. If a rule changes, we update your setup and tell you what changed.</p>'),
  ]),
  ib_paragraph('faq', [
    'heading' => 'Dual pricing questions',
    'layout' => 'centered',
    'items' => [
      $faq('Will my customers be upset?', 'Most customers already see cash and card prices at gas stations, so it rarely surprises anyone. Clear signage does the explaining, and cash stays an option for anyone who wants the lower price.'),
      $faq('How much higher is the card price?', 'Enough to cover your processing cost, and no more. We set it with you during setup, show it on every sign and receipt, and put it in your quote in writing.'),
      $faq('Does it apply to debit cards too?', 'Yes. Under dual pricing the card price applies to every card, credit or debit. That keeps it simple at the register and consistent with card-brand rules.'),
      $faq('Can I use dual pricing online?', 'Yes. Online checkout can show both prices before the customer pays. Tell us how you sell online and we will set it up to match your counter.'),
      $faq('What does it cost me each month?', 'Dual pricing has one monthly program fee that covers the gateway, your dashboard and PCI compliance, plus any hardware you lease. Your quote lists every line so there are no surprises.'),
      $faq('Can I switch back later?', 'Yes. If dual pricing is not right for your business, we can move you to Pro or Simple. We will review the numbers with you first.'),
      $faq('Do I need new hardware?', 'Not always. Many Dejavoo and Valor terminals can be reprogrammed for dual pricing. If yours cannot, we will recommend a device that fits your counter and budget.'),
    ],
  ]),
  $related,
  ib_paragraph('cta_band', [
    'heading' => 'Ready to stop paying to get paid?',
    'accent_word' => 'paying',
    'body' => "Send a recent statement and we'll show you, in writing, what dual pricing would change.",
    'link' => ib_link('/get-a-quote', 'Get a dual pricing quote'),
    'link_secondary' => ib_link('/pricing#statement', 'Upload a statement'),
  ]),
];

$node->set('field_sections', $sections);
$node->set('field_meta_description', 'Dual pricing shows a cash price and a card price so card customers cover processing. Signage, receipts and setup handled from Austin.');
$node->save();
foreach ($existing as $section) {
  if ($section->id() !== $estimator->id() && $section->id() !== $related->id()) {
    $section->delete();
  }
}
print "Dual pricing page revised.\n";
