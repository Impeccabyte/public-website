<?php

/**
 * @file
 * The On the go solution page, as designed and approved on 2026-11-19.
 *
 * Devices come from the hardware catalog (scripts/content/30-devices.php).
 * Offline mode is described only for Clover devices, as on the Restaurants
 * page. Supported phones for tap to pay are a placeholder until confirmed.
 * No prices, no funding times.
 *
 * Safe to re-run. A new site gets the page; a site that still has the short
 * "more detail is on the way" page at /solutions/on-the-go has its sections
 * replaced; a page edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/49-on-the-go.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/solutions/on-the-go';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;
// Catalog links filtered to one device type.
$catalog = function (string $type): string {
  $tid = ib_term_id('device_type', $type);
  return $tid ? "/hardware?type[$tid]=$tid" : '/hardware';
};
$setup = fn(string $title, string $body, string $note, string $photo, string $link, string $url) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'note' => $note, 'media_label' => $photo, 'link' => ib_link($url, $link)];

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Mobile payments',
    'heading' => 'Get paid wherever the work is.',
    'accent_word' => 'wherever',
    'intro' => 'Pocket-size readers, handhelds with their own connection and tap to pay on your phone, for markets, pop-ups, job sites and deliveries. All on one merchant account, with a local team to call.',
    'link' => ib_link('/get-a-quote', 'Get a mobile quote'),
    'link_secondary' => ib_link($catalog('Mobile Reader'), 'See mobile readers'),
    'checklist' => ['Chip, tap and swipe', 'Wi-Fi or cellular', 'Support from Austin'],
    'media_label' => 'a market vendor taking a tap payment on a small reader',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Pick your setup',
    'heading' => 'Three ways to take payments away from the counter',
    'intro' => 'Start small and add a device when the line gets longer. Every option runs on the same account, so your deposits and reports stay in one place.',
    'items' => [
      $setup('Tap to pay on your phone', 'Take contactless cards and phone wallets on the phone you already carry. No extra hardware to buy or charge.', 'Good for: occasional sales, a backup at busy events', 'a customer tapping a card on the back of a phone', 'Ask about tap to pay', '/get-a-quote'),
      $setup('A reader that pairs with your phone', 'A pocket-size reader for chip, tap and swipe that connects to your phone or tablet by Bluetooth.', 'Good for: markets, pop-ups, home services', 'a small reader beside a phone on a market table', 'See mobile readers', $catalog('Mobile Reader')),
      $setup('A handheld with its own connection', 'An all-in-one device with a built-in printer that runs on Wi-Fi or cellular, depending on the model.', 'Good for: food trucks, delivery, curbside', 'a handheld terminal printing a receipt outdoors', 'See handhelds', $catalog('Handheld')),
    ],
  ]],
  ['card_grid', [
    'eyebrow' => 'Mobile devices',
    'heading' => 'Readers and handhelds we set up most',
    'intro' => 'Every device ships programmed to your account and ready for its first sale.',
    'link' => ib_link($catalog('Mobile Reader'), 'See all mobile readers'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['clover-go', 'swipesimple-b350', 'clover-flex', 'pax-a920-pro']))),
  ]],
  ['steps', [
    'eyebrow' => 'How it works',
    'heading' => 'From a quote to your first sale on the road',
    'items' => [
      $step('Tell us where you sell', 'Markets, job sites or a truck, how often, and roughly what you take in each month.'),
      $step('Get a quote in writing', 'We recommend a device and a pricing program and list every fee before you sign.'),
      $step('We program your device', 'It arrives set up to your account, paired and ready. We walk you through it by phone or in person.'),
      $step('Take your first sale', 'Run a test sale with us, then head out. We stay your local point of contact.'),
    ],
  ]],
  ['media_split', [
    'eyebrow' => 'Out in the field',
    'heading' => 'Keep selling when the signal drops',
    'body' => "Parking lots, fairgrounds and back roads don't always have good Wi-Fi. We match the device to where you work, so a weak signal doesn't stop the line.",
    'checklist' => [
      'Cellular handhelds connect on their own, with a data plan if you need one',
      'Clover devices can take payments offline for a period and send them once you reconnect',
      "Receipts by text or email, so you don't need paper on the road",
    ],
    'link' => ib_link('/industries/food-trucks', 'See food truck setups'),
    'media_label' => 'a handheld passed through a food truck window',
  ]],
  ['callout', [
    'callout_variant' => 'brand',
    'title' => 'Tapped cards usually cost less than keyed-in ones.',
    'text' => ib_html('<p>When the card is there, tap or dip it rather than typing the number in. Present cards carry lower processing costs and less fraud risk. We put every rate and device cost in writing before you sign. <a href="/pricing">See how the pricing programs compare</a>.</p>'),
  ]],
  ['faq', [
    'heading' => 'Mobile payment questions',
    'layout' => 'centered',
    'items' => [
      $faq('Can I take payments on my own phone?', 'Yes, with tap to pay you can accept contactless cards and phone wallets on a supported phone with no extra hardware. [Supported phones and setup steps to be confirmed.] For chip cards, add a reader that pairs with your phone.'),
      $faq('Do I need a data plan for my device?', "Readers that pair with your phone use your phone's connection. Handhelds connect over Wi-Fi, and some models also take a cellular data plan, which we can add if you work where Wi-Fi is not available."),
      $faq('What happens if I lose signal?', 'Clover devices can keep taking payments offline for a period and send them once you reconnect. Offline payments carry some risk if a card is later declined, so we explain the limits when we set you up.'),
      $faq('Can I use the same account at my shop and on the road?', 'Yes. Mobile devices run on the same merchant account as your counter terminal or online checkout, so your deposits and reports stay together.'),
      $faq('How fast will I get my money?', 'Deposit timing depends on your account and on when you close your batch each day. Funding cutoffs vary by processor, and we walk you through yours when we set you up.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['clover-flex-first-tableside-payment', 'merchant-account-checklist', 'funding-times-explained']))),
  ]],
  ['cta_band', [
    'heading' => "Tell us where you sell. We'll make it portable.",
    'accent_word' => 'portable',
    'body' => 'Markets, job sites, a truck or a trunk. A local specialist replies within one business day with a setup that fits.',
    'link' => ib_link('/get-a-quote', 'Get a mobile quote'),
    'link_secondary' => ib_link('/hardware', 'Browse hardware'),
  ]],
];

$meta = 'Mobile card readers, cellular handhelds and tap to pay on your phone for markets, pop-ups and field work. Set up and supported from Austin.';
$node = ib_node_by_alias($path);
if (!$node) {
  ib_page('solution', 'On the Go', $path, $sections, [
    'field_category' => ['target_id' => ib_term_id('solution_category', 'Accept Payments')],
    'field_short_description' => 'Mobile readers and tap to pay on your phone',
    'field_icon' => 'smartphone',
    'field_meta_description' => $meta,
  ]);
  print "On the go page created.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  $node->set('field_meta_description', $meta);
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "On the go page rebuilt from its short version.\n";
}
else {
  print "On the go page already built; left as it is.\n";
}
