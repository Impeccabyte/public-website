<?php

/**
 * @file
 * The Pop-ups and markets industry page, 2026-11-19.
 *
 * Built on the Restaurants and bars page's pattern. The sample setup follows
 * the Home page's pop-up chip: a SwipeSimple B350 reader, tap to pay on your
 * phone as a backup and flat-rate pricing, named in the note. Tap to pay
 * claims match the On the go page and share its to-confirm item.
 *
 * Safe to re-run. A site that still has the short "more detail is on the
 * way" page at /industries/pop-ups-and-markets has its sections replaced; a
 * page edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/83-pop-ups.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/pop-ups-and-markets';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;
$catalog = function (string $type): string {
  $tid = ib_term_id('device_type', $type);
  return $tid ? "/hardware?type[$tid]=$tid" : '/hardware';
};
$setup = fn(string $title, string $body, string $note, string $photo, string $link, string $url) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'note' => $note, 'media_label' => $photo, 'link' => ib_link($url, $link)];

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Pop-Ups & Markets',
    'heading' => 'Set up in five minutes, sell all weekend.',
    'accent_word' => 'weekend',
    'intro' => 'A reader that pairs with your phone, tap to pay as a backup, and no long setup when you are new. One local team to call when the market opens early.',
    'link' => ib_link('/get-a-quote', 'Get a market quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
    'checklist' => ['Works with your phone', 'Chip, tap and swipe', 'Support from Austin'],
    'media_label' => 'a maker selling at a weekend market booth',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Sound familiar?',
    'heading' => 'What makers and vendors tell us first',
    'items' => [
      $card('users', '“I lose sales when people have no cash.”', 'Take every card and phone wallet, so nobody walks away to find an ATM.'),
      $card('wifi', '“The market Wi-Fi never works.”', 'A reader that pairs with your phone uses your phone\'s own connection.'),
      $card('clock', '“I don\'t have time for a big setup.”', 'Pair the reader, add your items and you are ready before the first customer.'),
      $card('circle-percent', '“I want simple pricing while I grow.”', 'A flat rate is easy to budget while you are starting out. We show you when another program costs less.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Ways to sell',
    'heading' => 'At the booth, on your phone and online',
    'intro' => 'Start with a reader and add the others when you need them. They run on one merchant account, with one set of deposits.',
    'items' => [
      $setup('At the booth', 'A pocket reader for chip, tap and swipe that pairs with your phone by Bluetooth.', 'Good for: farmers markets, craft fairs, flea markets', 'a reader on a market table beside handmade goods', 'See mobile readers', $catalog('Mobile Reader')),
      $setup('On your phone', 'Tap to pay on your phone as a backup when the reader needs a charge.', 'Good for: a second checkout, busy hours', 'a customer tapping a card on a vendor\'s phone', 'See mobile payments', '/solutions/on-the-go'),
      $setup('Between markets', 'Payment links and an online shop on the same account, for preorders and regulars.', 'Good for: preorders, custom orders, restocks', 'a maker packing an order in her home studio', 'See online payments', '/solutions/online'),
    ],
  ]],
  ['bundle', [
    'eyebrow' => 'Recommended setup',
    'heading' => 'A setup for your first market season',
    'intro' => 'A starting point we tune to your booth, your products and your volume. Swap any piece for something that fits better.',
    'anchor' => 'setup',
    'items' => array_values(array_filter([
      ($reader = $node_ref('/hardware/swipesimple-b350')) ? ['_type' => 'bundle_item', 'label' => 'At the booth', 'reference' => $reader, 'note' => 'Chip, tap and swipe through your phone', 'quantity' => 1] : NULL,
      ($online = $node_ref('/solutions/online')) ? ['_type' => 'bundle_item', 'label' => 'Between markets', 'reference' => $online, 'note' => 'Payment links for preorders and custom orders', 'quantity' => 1] : NULL,
    ])),
    'addons' => ['tap to pay on your phone', 'a second reader'],
    'note' => 'Processing is quoted separately. Flat-rate pricing keeps it simple while you are starting out.',
    'link' => ib_link('/get-a-quote', 'Quote this setup'),
  ]],
  ['media_split', [
    'eyebrow' => 'Market day',
    'heading' => 'Ready before the first customer',
    'body' => 'Market mornings are short. The setup is simple enough to learn the night before and quick enough to keep a line moving.',
    'checklist' => [
      'Pair the reader with your phone in a minute',
      'Take tap, chip, swipe and phone wallets',
      'Receipts by text or email, no paper to carry',
      'See the whole day\'s sales on your phone before you pack up',
    ],
    'link' => ib_link('/solutions/on-the-go', 'See mobile payments'),
    'media_label' => 'a vendor setting up her booth at a weekend market',
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Readers vendors choose most',
    'intro' => 'Every reader ships ready to pair with your phone.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['swipesimple-b350', 'clover-go', 'clover-flex']))),
  ]],
  ['faq', [
    'heading' => 'Pop-up and market questions',
    'layout' => 'centered',
    'items' => [
      $faq('Do I need Wi-Fi at the market?', 'No. A reader that pairs with your phone uses your phone\'s connection. If the signal is weak, a handheld with its own cellular connection is an option.'),
      $faq('Can I use tap to pay on my phone instead of a reader?', 'Tap to pay on your phone takes contactless cards and phone wallets with no extra hardware, so it works well as a backup. For chip cards, add a reader.'),
      $faq('I am just starting out. Is that a problem?', 'No. Many vendors start with us before their first market. Tell us what you sell and we will walk you through the application.'),
      $faq('Which pricing should I start with?', 'Flat-rate pricing is the simplest to budget while you are starting out. As your sales grow, we will show you if another program costs less.'),
      $faq('When do I get paid after a market?', 'Deposit timing depends on when your batch closes, your processor\'s cutoff, weekends and holidays. Cutoffs vary by processor, and we walk you through yours when we set you up.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['first-merchant-account', 'merchant-account-checklist', 'funding-times-explained']))),
  ]],
  ['cta_band', [
    'heading' => "Let's get you ready for Saturday.",
    'accent_word' => 'ready',
    'body' => 'Tell us what you sell and where. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a market quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
  ]],
];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No pop-ups page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  if ($node->hasField('field_meta_description')) {
    $node->set('field_meta_description', 'Card payments for Austin pop-ups and market vendors: a reader that pairs with your phone, tap to pay as a backup and simple pricing.');
  }
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "Pop-ups page rebuilt from its short version.\n";
}
else {
  print "Pop-ups page already built; left as it is.\n";
}
