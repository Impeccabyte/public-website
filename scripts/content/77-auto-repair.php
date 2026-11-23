<?php

/**
 * @file
 * The Auto repair industry page, 2026-11-19.
 *
 * Built on the Restaurants and bars page's pattern. The sample setup follows
 * the Home page's auto repair chip: a Valor VP550 terminal, dual pricing
 * and chargeback protection. Fraud tools named are the ones on the
 * Chargeback protection page (address and code checks). No prices in text.
 *
 * Safe to re-run. A site that still has the short "more detail is on the
 * way" page at /industries/auto-repair has its sections replaced; a page
 * edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/77-auto-repair.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/auto-repair';
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
    'eyebrow' => 'Auto Repair',
    'heading' => 'Close out big tickets with confidence.',
    'accent_word' => 'confidence',
    'intro' => 'Higher-ticket payments with fraud checks, plus text-to-pay so customers can approve and pay before pickup. One local team for your setup and support.',
    'link' => ib_link('/get-a-quote', 'Get a shop quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
    'checklist' => ['Text-to-pay before pickup', 'Fraud checks on big tickets', 'Dual pricing ready'],
    'media_label' => 'a customer paying at an auto repair shop counter',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Sound familiar?',
    'heading' => 'What shop owners tell us first',
    'items' => [
      $card('circle-percent', '“Fees on big repairs really hurt.”', 'Dual pricing lets card customers cover processing, which matters most on the largest tickets.'),
      $card('clock', '“Customers wait at the counter to pay.”', 'Text a payment link when the car is ready, so customers pay before they arrive and just grab the keys.'),
      $card('shield-check', '“A customer disputed a repair.”', 'Signed approvals and alerts the moment a dispute opens give you the best chance of winning it.'),
      $card('truck', '“Fleet accounts pay slowly.”', 'Invoice fleet customers by email and take card or ACH, so repeat accounts pay on time.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Ways to get paid',
    'heading' => 'At the counter, by text and through your shop software',
    'intro' => 'Most shops use all three. They run on one merchant account, so every payment lands in the same reports.',
    'items' => [
      $setup('At the counter', 'A countertop terminal that is ready for dual pricing and quick with tap, chip and phone wallets.', 'Good for: walk-ins, pickups, parts sales', 'a countertop card terminal on a shop counter', 'See countertop terminals', $catalog('Countertop Terminal')),
      $setup('By text', 'Send the approval and a payment link by text, so the bill is settled before the customer walks in.', 'Good for: pickups, repairs over the phone', 'a customer approving a repair on her phone', 'See online payments', '/solutions/online'),
      $setup('In your shop software', 'Connect payments to your shop management or accounting software, so invoices close themselves.', 'Good for: busy shops, fleet accounts', 'a service advisor at the front desk computer', 'See integrations', '/solutions/integrations'),
    ],
  ]],
  ['bundle', [
    'eyebrow' => 'Recommended setup',
    'heading' => 'A setup for a four-bay shop',
    'intro' => 'A starting point we tune to your bays, your customers and your volume. Swap any piece for something that fits better.',
    'anchor' => 'setup',
    'items' => array_values(array_filter([
      ($valor = $node_ref('/hardware/valor-vp550')) ? ['_type' => 'bundle_item', 'label' => 'At the counter or in the bay', 'reference' => $valor, 'note' => 'Android terminal built for dual pricing, on Wi-Fi or 4G', 'quantity' => 1] : NULL,
      ($dual = $node_ref('/solutions/dual-pricing')) ? ['_type' => 'bundle_item', 'label' => 'Pricing', 'reference' => $dual, 'note' => 'Card customers cover processing on big repairs', 'quantity' => 1] : NULL,
      ($cb = $node_ref('/solutions/chargeback-protection')) ? ['_type' => 'bundle_item', 'label' => 'Disputes', 'reference' => $cb, 'note' => 'Alerts and fraud checks before disputes escalate', 'quantity' => 1] : NULL,
    ])),
    'addons' => ['text-to-pay', 'ACH for fleet accounts'],
    'note' => 'Processing and gateway fees are quoted separately.',
    'link' => ib_link('/get-a-quote', 'Quote this setup'),
  ]],
  ['media_split', [
    'eyebrow' => 'Big tickets',
    'heading' => 'Paid in full before the keys change hands',
    'body' => 'Large repairs deserve a payment process that is quick for the customer and solid for you if a question comes up later.',
    'checklist' => [
      'Text the approval and a payment link as soon as the car is ready',
      'Address and security code checks on every card',
      'Keep signed approvals and photos to answer any dispute',
      'Get an alert the moment a chargeback is opened',
    ],
    'link' => ib_link('/solutions/chargeback-protection', 'See chargeback protection'),
    'media_label' => 'a technician photographing a finished repair on a lift',
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Devices shops choose most',
    'intro' => 'Every device ships programmed to your account and ready for the next pickup.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['valor-vp550', 'dejavoo-z8', 'clover-mini', 'pax-a920-pro']))),
  ]],
  ['faq', [
    'heading' => 'Auto Repair questions',
    'layout' => 'centered',
    'items' => [
      $faq('Can customers pay before they pick up?', 'Yes. Text a payment link when the car is ready and the customer pays by card from their phone. When they arrive, they just pick up the keys.'),
      $faq('Does dual pricing work for repairs?', 'Yes. Signs at the counter explain the two prices, and the terminal and receipt show both. On large tickets, it can make the biggest difference to your fees.'),
      $faq('How do I protect against disputes on big repairs?', 'Get the approval in writing, keep photos of the work, and answer quickly. You get an alert as soon as a chargeback is opened and can upload your evidence from the dashboard.'),
      $faq('Can fleet customers pay by ACH?', 'Yes. The virtual terminal takes card and ACH payments, which can cost less on large invoices.'),
      $faq('Will it work with my shop management software?', 'Often. Many shop management systems connect to a payment gateway. Tell us which one you use and we will check before you switch.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['customer-disputes-a-charge', 'boutique-switching-to-dual-pricing', 'payment-links-vs-online-store']))),
  ]],
  ['cta_band', [
    'heading' => "Let's make pickup the easy part.",
    'accent_word' => 'pickup',
    'body' => 'Tell us about your shop, your bays and your software. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a shop quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
  ]],
];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No auto repair page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  if ($node->hasField('field_meta_description')) {
    $node->set('field_meta_description', 'Card payments for Austin auto repair shops: text-to-pay before pickup, dual pricing on big repairs, fraud checks and chargeback alerts.');
  }
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "Auto repair page rebuilt from its short version.\n";
}
else {
  print "Auto repair page already built; left as it is.\n";
}
