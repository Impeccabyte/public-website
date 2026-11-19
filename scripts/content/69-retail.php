<?php

/**
 * @file
 * The Retail shops industry page, 2026-11-19.
 *
 * Built on the Restaurants and bars page's pattern. The sample setup follows
 * the Home page's retail chip: Clover Mini at checkout, a barcode scanner and
 * interchange plus pricing. The scanner's monthly price is the wireframe's
 * seed value in a number field, so it follows the Show prices setting.
 *
 * Safe to re-run. A site that still has the short "more detail is on the
 * way" page at /industries/retail has its sections replaced; a page edited
 * since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/69-retail.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/retail';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;
$catalog = function (string $type): string {
  $tid = ib_term_id('device_type', $type);
  return $tid ? "/hardware?type[$tid]=$tid" : '/hardware';
};
$setup = fn(string $title, string $body, string $note, string $photo, string $link, string $url) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'note' => $note, 'media_label' => $photo, 'link' => ib_link($url, $link)];
$ic_plus = ib_node_by_alias('/solutions/interchange-plus');

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Retail shops',
    'heading' => 'A counter that keeps up with Saturday.',
    'accent_word' => 'Saturday',
    'intro' => 'Barcode scanning, inventory and fast tap payments, with a setup that grows from one register to three. One local team for your hardware, pricing and support.',
    'link' => ib_link('/get-a-quote', 'Get a retail quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
    'checklist' => ['Barcode scanning', 'Inventory on your POS', 'Support from Austin'],
    'media_label' => 'a boutique owner ringing up a sale at the counter',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Sound familiar?',
    'heading' => 'What shop owners tell us first',
    'items' => [
      $card('users', '“The line backs up on Saturdays.”', 'Add a second register or a handheld to check customers out from anywhere on the floor.'),
      $card('list-checks', '“I never know what\'s in stock.”', 'Clover POS systems count inventory down with every sale, so stock levels stay current.'),
      $card('receipt', '“Returns take forever.”', 'Find the sale on the POS and refund it to the original card in a few taps.'),
      $card('circle-percent', '“Fees on every sale add up.”', 'We compare interchange plus and dual pricing on your real statement and show you which costs less.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Ways to sell',
    'heading' => 'At the counter, on the floor and online',
    'intro' => 'Start with the counter and add the others when you need them. They all run on one merchant account, with one set of deposits.',
    'items' => [
      $setup('At the counter', 'A POS with a barcode scanner that rings up sales, tracks stock and prints or texts receipts.', 'Good for: boutiques, gift shops, convenience', 'a shop counter with a POS and barcode scanner', 'See POS systems', $catalog('POS system')),
      $setup('On the floor', 'A handheld that checks customers out wherever they are, so the line never forms.', 'Good for: busy weekends, sidewalk sales, events', 'a clerk checking out a customer between the racks', 'See handhelds', $catalog('Handheld')),
      $setup('Online', 'Payment links and online checkout on the same account, for orders by message or a web store.', 'Good for: local delivery, preorders, web shops', 'an owner packing an online order in the stockroom', 'See online payments', '/solutions/online'),
    ],
  ]],
  ['bundle', [
    'eyebrow' => 'Recommended setup',
    'heading' => 'A setup for a two-register shop',
    'intro' => 'A starting point we tune to your space, your catalog and your volume. Swap any piece for something that fits better.',
    'anchor' => 'setup',
    'items' => array_values(array_filter([
      ($mini = $node_ref('/hardware/clover-mini')) ? ['_type' => 'bundle_item', 'label' => 'Checkout', 'reference' => $mini, 'note' => 'One at each register, with inventory and reporting', 'quantity' => 2] : NULL,
      ['_type' => 'bundle_item', 'label' => 'Scanning', 'title' => 'Barcode scanner', 'note' => 'Scan items straight into the sale', 'quantity' => 2, 'monthly_price' => 10],
      ($flex = $node_ref('/hardware/clover-flex')) ? ['_type' => 'bundle_item', 'label' => 'On the floor', 'reference' => $flex, 'note' => 'Check out customers in line on busy days', 'quantity' => 1] : NULL,
      $ic_plus ? ['_type' => 'bundle_item', 'label' => 'Pricing', 'reference' => ['target_id' => $ic_plus->id()], 'note' => 'The real cost of each card, plus one clear markup', 'quantity' => 1] : NULL,
    ])),
    'addons' => ['receipt printer', 'cash drawer', 'online checkout'],
    'note' => 'Processing and Clover software are quoted separately.',
    'link' => ib_link('/get-a-quote', 'Quote this setup'),
  ]],
  ['media_split', [
    'eyebrow' => 'Busy days',
    'heading' => 'Built for your busiest Saturday',
    'body' => 'The best sales day of the week should not be the most stressful one. The setup is chosen to keep the line short and the counts right.',
    'checklist' => [
      'Scan items straight into the sale, no typing prices',
      'Stock counts go down with every sale, so reorders are easy',
      'Tap, chip, swipe and phone wallets on every device',
      'Receipts printed, texted or emailed, whatever the customer prefers',
    ],
    'link' => ib_link('/solutions/in-person', 'See in-person payments'),
    'media_label' => 'a busy weekend at a boutique checkout',
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Devices shops choose most',
    'intro' => 'Every device ships programmed to your account and ready to scan.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['clover-mini', 'clover-station-solo', 'clover-flex', 'clover-compact']))),
  ]],
  ['faq', [
    'heading' => 'Retail questions',
    'layout' => 'centered',
    'items' => [
      $faq('Can you load my products for me?', 'We help you get your items onto the POS, usually from a spreadsheet of names, prices and barcodes, so you are ready to sell on day one.'),
      $faq('Do barcode scanners work with Clover?', 'Yes. A scanner connects to your Clover POS, and scanned items go straight into the sale at the price you set.'),
      $faq('How do refunds work?', 'Find the sale on your POS and refund it to the original card. The refund comes out of your next deposit.'),
      $faq('Which pricing is best for a shop?', 'It depends on your sales. Interchange plus often costs least for steady, higher volume, while dual pricing suits lots of small tickets. We compare both on your real statement.'),
      $faq('Can I sell online too?', 'Yes. Payment links and online checkout run on the same merchant account, so your sales and deposits stay together.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['boutique-switching-to-dual-pricing', 'read-a-processing-statement', 'payment-links-vs-online-store']))),
  ]],
  ['cta_band', [
    'heading' => "Let's set up a counter that keeps up.",
    'accent_word' => 'keeps',
    'body' => 'Tell us about your shop, your registers and your catalog. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a retail quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
  ]],
];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No retail page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  if ($node->hasField('field_meta_description')) {
    $node->set('field_meta_description', 'Card payments for Austin retail shops: barcode scanning, inventory on your POS, handhelds for busy days and clear pricing.');
  }
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "Retail page rebuilt from its short version.\n";
}
else {
  print "Retail page already built; left as it is.\n";
}
