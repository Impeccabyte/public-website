<?php

/**
 * @file
 * The Professional services industry page, 2026-11-19.
 *
 * Built on the Restaurants and bars page's pattern. The sample setup follows
 * the Home page's professional services chip: an online gateway with a
 * virtual terminal and invoicing, ACH for large invoices, and recurring
 * billing for retainers. It makes no claims about legal trust accounts.
 *
 * Safe to re-run. A site that still has the short "more detail is on the
 * way" page at /industries/professional-services has its sections replaced;
 * a page edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/79-professional-services.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/professional-services';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;
$setup = fn(string $title, string $body, string $note, string $photo, string $link, string $url) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'note' => $note, 'media_label' => $photo, 'link' => ib_link($url, $link)];

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Professional services',
    'heading' => 'Invoices that pay themselves.',
    'accent_word' => 'themselves',
    'intro' => 'Send invoices, take ACH or card, and keep retainers on autopay without chasing anybody. One local team for your setup and support.',
    'link' => ib_link('/get-a-quote', 'Get a quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
    'checklist' => ['Card and ACH', 'Retainers on autopay', 'Support from Austin'],
    'media_label' => 'a consultant sending an invoice from her studio',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Sound familiar?',
    'heading' => 'What firm owners tell us first',
    'items' => [
      $card('clock', '“I spend Fridays chasing invoices.”', 'Every invoice goes out with a payment link, so clients pay the day it lands instead of the day you call.'),
      $card('receipt', '“Big clients still pay by check.”', 'Offer ACH alongside card. It can cost less on large invoices, and it arrives without a trip to the bank.'),
      $card('repeat', '“Retainers renew late.”', 'Put retainers on a schedule, and they charge the saved card or bank account on the day they are due.'),
      $card('file-text', '“Payments don\'t match my books.”', 'Sync payments and fees to your accounting software, so every invoice is marked paid on its own.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Ways to get paid',
    'heading' => 'Invoices, phone payments and retainers',
    'intro' => 'Most firms use all three. They run on one merchant account, so every payment lands in the same reports.',
    'items' => [
      $setup('Invoices', 'Email or text an invoice with a secure payment link. Clients pay by card or bank account.', 'Good for: projects, hourly work, one-off jobs', 'a client paying an invoice on a laptop', 'See online payments', '/solutions/online'),
      $setup('Over the phone', 'A virtual terminal for card or ACH payments clients give you by phone, from any browser.', 'Good for: client calls, older clients, deposits', 'a bookkeeper taking a payment over the phone', 'See online payments', '/solutions/online'),
      $setup('Retainers', 'Monthly retainers and payment plans that charge on schedule, with expired cards updated for you.', 'Good for: ongoing clients, advisory work', 'a consultant meeting a client in a small office', 'See recurring billing', '/solutions/recurring-billing'),
    ],
  ]],
  ['bundle', [
    'eyebrow' => 'Recommended setup',
    'heading' => 'A setup for a small firm',
    'intro' => 'A starting point we tune to your clients, your billing and your volume. Swap any piece for something that fits better.',
    'anchor' => 'setup',
    'items' => array_values(array_filter([
      ($online = $node_ref('/solutions/online')) ? ['_type' => 'bundle_item', 'label' => 'Invoicing', 'reference' => $online, 'note' => 'Invoices, payment links and a virtual terminal', 'quantity' => 1] : NULL,
      ($recurring = $node_ref('/solutions/recurring-billing')) ? ['_type' => 'bundle_item', 'label' => 'Retainers', 'reference' => $recurring, 'note' => 'Monthly retainers that charge on schedule', 'quantity' => 1] : NULL,
      ($integrations = $node_ref('/solutions/integrations')) ? ['_type' => 'bundle_item', 'label' => 'Bookkeeping', 'reference' => $integrations, 'note' => 'Payments synced to your accounting software', 'quantity' => 1] : NULL,
    ])),
    'addons' => ['ACH payments', 'card on file'],
    'note' => 'Processing, gateway and any app fees are quoted separately.',
    'link' => ib_link('/get-a-quote', 'Quote this setup'),
  ]],
  ['media_split', [
    'eyebrow' => 'Billing',
    'heading' => 'Get paid like a bigger firm',
    'body' => 'Clients expect to pay online. A simple, professional way to do it means fewer reminders and steadier cash flow.',
    'checklist' => [
      'A payment link on every invoice, by email or text',
      'Card or ACH, whichever the client prefers',
      'A card or bank account saved, with permission, for retainers',
      'Payments and fees synced to your accounting software',
    ],
    'link' => ib_link('/solutions/integrations', 'See integrations'),
    'media_label' => 'two partners reviewing billing together at a small firm',
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'For clients who pay in person',
    'intro' => 'Most firms bill online, but a small device at the front desk covers walk-ins and appointments.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['clover-compact', 'dejavoo-z8', 'clover-go']))),
  ]],
  ['faq', [
    'heading' => 'Professional services questions',
    'layout' => 'centered',
    'items' => [
      $faq('Can clients pay invoices online?', 'Yes. Each invoice goes out with a secure payment link, and clients pay by card or bank account from any device.'),
      $faq('Can I put a client on a monthly retainer?', 'Yes. Set the amount and the day, and the retainer charges the saved card or bank account on schedule. Expired cards are updated for you.'),
      $faq('Can I take a card over the phone?', 'Yes. The virtual terminal works from any browser for card and ACH payments clients give you by phone.'),
      $faq('Is ACH cheaper than card?', 'It can be, especially on large invoices. We show you the cost of each in writing so you can decide what to offer.'),
      $faq('Will payments show up in my accounting software?', 'They can. Several gateways and partner apps sync payments to QuickBooks and other accounting tools. Tell us what you use and we\'ll recommend the simplest route.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['payment-links-vs-online-store', 'read-a-processing-statement', 'funding-times-explained']))),
  ]],
  ['cta_band', [
    'heading' => 'Spend Friday on clients, not collections.',
    'accent_word' => 'clients',
    'body' => 'Tell us how you bill and what software you use. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
  ]],
];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No professional services page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  if ($node->hasField('field_meta_description')) {
    $node->set('field_meta_description', 'Payments for Austin professional services firms: invoices with payment links, card and ACH, retainers on autopay and accounting sync.');
  }
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "Professional services page rebuilt from its short version.\n";
}
else {
  print "Professional services page already built; left as it is.\n";
}
