<?php

/**
 * @file
 * The Nonprofits industry page, 2026-11-19.
 *
 * Built on the Restaurants and bars page's pattern. The sample setup follows
 * the Home page's nonprofit chip: a hosted donation page, Clover Go for
 * events and recurring giving. It makes no claims about nonprofit pricing
 * programs or tax receipts.
 *
 * Safe to re-run. A site that still has the short "more detail is on the
 * way" page at /industries/nonprofits has its sections replaced; a page
 * edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/81-nonprofits.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/nonprofits';
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
    'eyebrow' => 'Nonprofits',
    'heading' => 'More of every gift goes to the mission.',
    'accent_word' => 'mission',
    'intro' => 'Online donation pages, event-day card readers and recurring giving for monthly supporters. One local team that answers the phone when your volunteers have a question.',
    'link' => ib_link('/get-a-quote', 'Get a nonprofit quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
    'checklist' => ['Online donation pages', 'Monthly giving on autopay', 'Support from Austin'],
    'media_label' => 'a volunteer taking a donation at a community event',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Sound familiar?',
    'heading' => 'What nonprofit teams tell us first',
    'items' => [
      $card('circle-percent', '“Fees eat into every gift.”', 'We compare pricing programs on your real statement and show you, in writing, which one keeps more of each donation.'),
      $card('globe', '“Our donate button is clunky.”', 'A hosted donation page drops into your website and works on any phone in a few taps.'),
      $card('users', '“Event day is chaos.”', 'Pocket readers let any volunteer take a card at the door, the auction table or the bake sale.'),
      $card('repeat', '“Monthly donors quietly drop off.”', 'Recurring giving updates expired cards for you, so a new card does not end a monthly gift.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Ways to give',
    'heading' => 'Online, at events and every month',
    'intro' => 'Most nonprofits use all three. They run on one merchant account, so every gift lands in the same reports.',
    'items' => [
      $setup('Online', 'A hosted donation page that matches your logo and drops into your website or a social post.', 'Good for: year-end appeals, campaigns', 'a donor giving online from her couch', 'See online payments', '/solutions/online'),
      $setup('At events', 'Pocket readers that pair with a volunteer\'s phone for tickets, auctions and donations at the door.', 'Good for: galas, fun runs, community days', 'a volunteer taking a card at an event table', 'See mobile readers', $catalog('Mobile Reader')),
      $setup('Every month', 'Recurring giving that charges on schedule and updates expired cards, so monthly gifts keep coming.', 'Good for: sustaining members, sponsors', 'volunteers packing donation boxes at a food bank', 'See recurring billing', '/solutions/recurring-billing'),
    ],
  ]],
  ['bundle', [
    'eyebrow' => 'Recommended setup',
    'heading' => 'A setup for a community nonprofit',
    'intro' => 'A starting point we tune to your donors, your events and your volume. Swap any piece for something that fits better.',
    'anchor' => 'setup',
    'items' => array_values(array_filter([
      ($online = $node_ref('/solutions/online')) ? ['_type' => 'bundle_item', 'label' => 'Donation page', 'reference' => $online, 'note' => 'A hosted page that drops into your website', 'quantity' => 1] : NULL,
      ($go = $node_ref('/hardware/clover-go')) ? ['_type' => 'bundle_item', 'label' => 'Event days', 'reference' => $go, 'note' => 'Readers that pair with volunteers\' phones', 'quantity' => 2] : NULL,
      ($recurring = $node_ref('/solutions/recurring-billing')) ? ['_type' => 'bundle_item', 'label' => 'Monthly giving', 'reference' => $recurring, 'note' => 'Monthly donors on autopay, with card updates', 'quantity' => 1] : NULL,
    ])),
    'addons' => ['ACH gifts', 'text-to-give links'],
    'note' => 'Processing and gateway fees are quoted separately.',
    'link' => ib_link('/get-a-quote', 'Quote this setup'),
  ]],
  ['media_split', [
    'eyebrow' => 'Event days',
    'heading' => 'Ready for your biggest night of the year',
    'body' => 'Galas, fun runs and community days bring in a year\'s worth of support in a few hours. The setup keeps the line moving so no gift is missed.',
    'checklist' => [
      'Readers any volunteer can learn in a few minutes',
      'Tap, chip and phone wallets at the door and the auction table',
      'Receipts by text or email, so donors leave with a record',
      'Every gift from the night in one report the next morning',
    ],
    'link' => ib_link('/solutions/on-the-go', 'See mobile payments'),
    'media_label' => 'a volunteer checking in guests at a community gala',
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Devices nonprofits choose most',
    'intro' => 'Every device ships programmed to your account and ready for your next event.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['clover-go', 'swipesimple-b350', 'clover-flex', 'clover-compact']))),
  ]],
  ['faq', [
    'heading' => 'Nonprofit questions',
    'layout' => 'centered',
    'items' => [
      $faq('Can donors give online from our website?', 'Yes. A hosted donation page connects to your website or a link in an email or social post, and donors give by card from any device.'),
      $faq('Can supporters give every month?', 'Yes. Recurring giving charges the amount a donor chooses on a set day and updates expired cards, so monthly gifts do not quietly stop.'),
      $faq('Can volunteers take donations at events?', 'Yes. Pocket readers pair with a volunteer\'s phone and take tap, chip and swipe. Every gift goes into the same account and reports.'),
      $faq('Do you offer special pricing for nonprofits?', 'We compare the pricing programs on your real statement and show you which one keeps the most of each gift. Every fee is in your quote, in writing.'),
      $faq('Do donors get a receipt?', 'Donors can get a payment receipt by text or email. Your own acknowledgment letters for tax purposes still come from your organization.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['payment-links-vs-online-store', 'funding-times-explained', 'read-a-processing-statement']))),
  ]],
  ['cta_band', [
    'heading' => 'Put more of every gift to work.',
    'accent_word' => 'work',
    'body' => 'Tell us about your donors, your events and your website. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a nonprofit quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
  ]],
];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No nonprofits page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  if ($node->hasField('field_meta_description')) {
    $node->set('field_meta_description', 'Payments for Austin nonprofits: online donation pages, event-day card readers and recurring giving that updates expired cards.');
  }
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "Nonprofits page rebuilt from its short version.\n";
}
else {
  print "Nonprofits page already built; left as it is.\n";
}
