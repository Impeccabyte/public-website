<?php

/**
 * @file
 * The In person solution page, as designed and approved on 2026-11-19.
 *
 * The copy reuses lines already approved elsewhere on the site. It has no
 * prices, no funding times and no invented figures; photos are labeled
 * placeholders until photography is supplied.
 *
 * Safe to re-run. A new site gets the page; a site that still has the short
 * "more detail is on the way" page at /solutions/in-person has its sections
 * replaced; a page that has been edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/45-in-person.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/solutions/in-person';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$card = fn(string $icon, string $title, string $body, bool $highlight = FALSE) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body, 'highlight' => (int) $highlight];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;
// Catalog links filtered to one device type.
$catalog = function (string $type): string {
  $tid = ib_term_id('device_type', $type);
  return $tid ? "/hardware?type[$tid]=$tid" : '/hardware';
};
$setup = fn(string $title, string $body, string $note, string $photo, string $link, string $type) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'note' => $note, 'media_label' => $photo, 'link' => ib_link($catalog($type), $link)];

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'In-person payments',
    'heading' => 'Take every card, right at your counter.',
    'accent_word' => 'counter',
    'intro' => 'Countertop terminals and full POS systems that take tap, chip and swipe. We help you pick the right device, program it for your account and stay your local point of contact after you open.',
    'link' => ib_link('/get-a-quote', 'Get an in-person quote'),
    'link_secondary' => ib_link('/hardware', 'Browse hardware'),
    'checklist' => ['Tap, chip and swipe', 'Ships programmed for your account', 'Support from Austin'],
    'media_label' => 'a customer tapping to pay at a shop counter',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'At the counter',
    'heading' => 'However your customers want to pay',
    'intro' => 'One device handles the cards and wallets people carry today, so nobody at your counter hears "sorry, we don\'t take that."',
    'items' => [
      $card('nfc', 'Tap and phone wallets', 'Contactless cards, Apple Pay and Google Pay, for the fastest line.'),
      $card('credit-card', 'Chip and swipe', 'EMV chip with PIN or signature, plus magstripe for older cards.'),
      $card('receipt', 'Tips and receipts', 'Take tips on the screen and hand over a printed receipt on devices with a built-in printer.'),
      $card('circle-percent', 'Cash and card prices', 'Dual pricing ready terminals show both prices, so card customers cover the processing cost.', TRUE),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Pick your setup',
    'heading' => 'Three ways to set up a counter',
    'intro' => "Start with how you sell. Not sure? Tell us about your counter and we'll recommend one.",
    'items' => [
      $setup('Countertop terminal', 'A small, simple device for taking payments. Good when you already have a register or just need to ring up a total.', 'Clover Compact · Dejavoo Z8 · Valor VP550', 'a countertop terminal beside a register', 'See countertop terminals', 'Countertop terminal'),
      $setup('Full POS system', 'Payments, orders, inventory and reporting on one screen. Good when the counter runs your whole front of house.', 'Clover Mini · Clover Station Solo · Clover Station Duo', 'a full POS station on a shop counter', 'See POS systems', 'POS system'),
      $setup('Handheld', "Bring the terminal to the table, the curb or the back of the line. Good when customers don't come to you.", 'Clover Flex · PAX A920 Pro', 'a handheld terminal being passed to a customer', 'See handhelds', 'Handheld'),
    ],
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Popular devices for the counter',
    'intro' => 'Buy it outright or lease it month to month. Every device ships programmed and ready to take its first payment.',
    'link' => ib_link('/hardware', 'Browse all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['clover-mini', 'clover-compact', 'dejavoo-z8', 'clover-station-duo']))),
  ]],
  ['steps', [
    'eyebrow' => 'How it works',
    'heading' => 'From a quote to your first sale',
    'items' => [
      $step('Tell us how you sell', 'Three short steps online, or a call. We ask about your counter, your volume and what you use today.'),
      $step('Get a quote in writing', 'We recommend a device and a pricing program, and list every fee before you sign anything.'),
      $step('Your device arrives programmed', 'It ships set up for your merchant account, so there is no configuration to work out on your own.'),
      $step('Take your first payment', 'Plug in, run a test sale with us and start ringing people up. We stay your local point of contact.'),
    ],
  ]],
  ['media_split', [
    'eyebrow' => 'Already have a terminal?',
    'heading' => 'You may not need new hardware',
    'body' => "If you already own compatible equipment, we can often reprogram it for you. Tell us the make and model and we'll check before you spend anything.",
    'checklist' => [
      'Many Dejavoo, PAX and Valor terminals can be reprogrammed',
      'If your POS supports a semi-integrated terminal, we can connect one instead of replacing everything',
      'If yours cannot be reused, we recommend one that fits your counter and budget',
    ],
    'link' => ib_link('/get-a-quote', 'Ask about your device'),
    'media_label' => "setting up a terminal a merchant already owns",
  ]],
  ['callout', [
    'callout_variant' => 'brand',
    'title' => 'The device is half of it. Pricing is the other half.',
    'text' => ib_html('<p>Every terminal runs on a pricing program: flat rate, interchange plus or dual pricing. We work out which one costs you least from your real statement and put every fee in writing. <a href="/pricing">See how the three compare</a>.</p>'),
  ]],
  ['faq', [
    'heading' => 'In-person payment questions',
    'layout' => 'centered',
    'items' => [
      $faq('Do I have to buy hardware?', 'No. You can buy a device outright or lease it month to month. If you already own compatible equipment, we can often reprogram it for you.'),
      $faq('Which cards and wallets can I take?', 'Tap, chip and swipe on the major card brands, plus Apple Pay and Google Pay. Some devices, such as Clover Mini, also take EBT. Tell us what your customers use and we will confirm it for the device you pick.'),
      $faq('Countertop terminal or full POS: how do I choose?', 'If you only need to take a payment for a total, a countertop terminal is the simpler choice. If you want orders, inventory and reporting on the same screen, look at a POS system. We will walk through it with you before you decide.'),
      $faq('What happens if the internet goes down?', 'Clover devices can keep taking payments offline for a period and send them once you reconnect. A cellular backup plan is also available. Ask us what your device supports.'),
      $faq('How fast will I get my money?', 'Deposit timing depends on your account and on when you close your batch each day. Funding cutoffs vary by processor, and we walk you through yours when we set you up.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['merchant-account-checklist', 'clover-flex-first-tableside-payment', 'read-a-processing-statement']))),
  ]],
  ['cta_band', [
    'heading' => "Tell us about your counter. We'll match the device.",
    'accent_word' => 'match',
    'body' => 'A local specialist replies within one business day. No pressure, no obligation.',
    'link' => ib_link('/get-a-quote', 'Get an in-person quote'),
    'link_secondary' => ib_link('/hardware', 'Browse hardware'),
  ]],
];

$fields = [
  'field_category' => ['target_id' => ib_term_id('solution_category', 'Accept payments')],
  'field_short_description' => 'Countertop terminals and full POS systems',
  'field_icon' => 'store',
  'field_meta_description' => 'Countertop terminals and full POS systems that take tap, chip and swipe, programmed for your account and supported from Austin.',
];

$node = ib_node_by_alias($path);
if (!$node) {
  ib_page('solution', 'In person', $path, $sections, $fields);
  print "In person page created.\n";
  return;
}
// Only the untouched short page is replaced.
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
$is_stub = $bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way';
if ($is_stub) {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  $node->set('field_meta_description', $fields['field_meta_description']);
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "In person page rebuilt from its short version.\n";
}
else {
  print "In person page already built; left as it is.\n";
}
