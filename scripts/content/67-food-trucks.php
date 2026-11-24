<?php

/**
 * @file
 * The Food trucks industry page, 2026-11-19.
 *
 * Built on the Restaurants and bars page's pattern: pain points, a sample
 * setup, an event-day section, devices, questions and reading. Device and
 * connectivity claims match the On the go page: Clover Flex runs on Wi-Fi or
 * cellular, Clover devices take payments offline for a period. Clover offers
 * a cash discount, not dual pricing (see 98-clover-cash-discount.php). No funding
 * times beyond "cutoffs vary by processor". Bundle prices come from the
 * devices, so they follow the Show prices setting.
 *
 * Safe to re-run. A site that still has the short "more detail is on the
 * way" page at /industries/food-trucks has its sections replaced; a page
 * edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/67-food-trucks.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/food-trucks';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Food Trucks',
    'heading' => 'Take cards anywhere you park.',
    'accent_word' => 'anywhere',
    'intro' => 'A handheld that keeps working when the Wi-Fi does not, prices that keep more of every small ticket, and deposits set up before your first shift. One local team for all of it.',
    'link' => ib_link('/get-a-quote', 'Get a food truck quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
    'checklist' => ['Works on Wi-Fi or cellular', 'Prints receipts at the window', 'Support from Austin'],
    'media_label' => 'a customer tapping a card at a food truck window',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Sound familiar?',
    'heading' => 'What food truck owners tell us first',
    'items' => [
      $card('wifi', '“The Wi-Fi drops at every event.”', 'A handheld with its own cellular connection keeps taking cards when the venue network is overloaded.'),
      $card('users', '“The line gets long at lunch.”', 'Take orders and payment down the line with a handheld, so the window stays free for pickup.'),
      $card('circle-percent', '“Fees eat a small ticket.”', 'A cash discount on your Clover gives customers who pay cash a lower price and helps offset what you pay to process cards.'),
      $card('clock', '“I never know when the money lands.”', 'We set up your deposits and show you how batching works. Funding cutoffs vary by processor.'),
    ],
  ]],
  ['bundle', [
    'eyebrow' => 'Recommended setup',
    'heading' => 'A setup for one truck',
    'intro' => 'A starting point we tune to your menu, your stops and your volume. Swap any piece for something that fits better.',
    'anchor' => 'setup',
    'items' => array_values(array_filter([
      ($flex = $node_ref('/hardware/clover-flex')) ? ['_type' => 'bundle_item', 'label' => 'At the window', 'reference' => $flex, 'note' => 'Takes cards and prints receipts, on Wi-Fi or cellular', 'quantity' => 1] : NULL,
      ($go = $node_ref('/hardware/clover-go')) ? ['_type' => 'bundle_item', 'label' => 'Down the line', 'reference' => $go, 'note' => 'A pocket reader with your phone for orders in line', 'quantity' => 1] : NULL,
    ])),
    'addons' => ['cellular data plan', 'online ordering', 'cash discount'],
    'note' => 'Processing, Clover software and any cellular data plan are quoted separately.',
    'link' => ib_link('/get-a-quote', 'Quote this setup'),
  ]],
  ['media_split', [
    'eyebrow' => 'Event days',
    'heading' => 'Ready for the festival rush',
    'body' => 'Big events mean long lines and crowded networks. We set you up so neither one stops a sale.',
    'checklist' => [
      'A cellular connection when the venue Wi-Fi is overloaded',
      'Clover devices keep taking payments offline for a period if the signal drops',
      'Tap and phone wallets for the fastest checkout in line',
      'Tips added on screen and paid with the day\'s batch',
    ],
    'link' => ib_link('/solutions/on-the-go', 'See mobile payments'),
    'media_label' => 'a long line at a food truck during an outdoor festival',
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Devices food trucks choose most',
    'intro' => 'Every device ships programmed to your menu and ready for your first stop.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['clover-flex', 'pax-a920-pro', 'clover-go', 'swipesimple-b350']))),
  ]],
  ['faq', [
    'heading' => 'Food truck questions',
    'layout' => 'centered',
    'items' => [
      $faq('What happens if I lose signal?', 'Clover devices can keep taking payments offline for a period and send them once you reconnect. Offline payments carry some risk if a card is later declined, so we explain the limits when we set you up.'),
      $faq('Do I need a data plan?', "A handheld with its own cellular connection needs a data plan, which we can add. A reader that pairs with your phone uses your phone's connection instead."),
      $faq('Can I offer a cash discount at the window?', "Yes. With Clover's cash discount, your posted prices are your card prices, and customers who pay cash get a discount at checkout. We set it up with you and explain how it works before your first shift."),
      $faq('Can I use the same setup at events and my regular spot?', 'Yes. Your devices, menu and account go wherever the truck goes, on Wi-Fi where there is good coverage and cellular where there is not.'),
      $faq('When do I get paid after an event?', 'Deposit timing depends on when you close your batch, your processor\'s cutoff, weekends and holidays. Cutoffs vary by processor, and we walk you through yours when we set you up.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['clover-flex-first-tableside-payment', 'funding-times-explained', 'merchant-account-checklist']))),
  ]],
  ['cta_band', [
    'heading' => "Let's get your window ready for the next event.",
    'accent_word' => 'ready',
    'body' => 'Tell us about your truck, your stops and your menu. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a food truck quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
  ]],
];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No food trucks page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  if ($node->hasField('field_meta_description')) {
    $node->set('field_meta_description', 'Card payments for Austin food trucks: cellular handhelds, offline mode, a cash discount on Clover and deposits set up before your first shift.');
  }
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "Food trucks page rebuilt from its short version.\n";
}
else {
  print "Food trucks page already built; left as it is.\n";
}
