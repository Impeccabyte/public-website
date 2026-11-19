<?php

/**
 * @file
 * The Salons and spas industry page, 2026-11-19.
 *
 * Built on the Restaurants and bars page's pattern. The sample setup follows
 * the Home page's salon chip: a Clover Compact at the front desk and
 * recurring billing for memberships, with flat-rate pricing named in the
 * note. Card on file is always described with the client's agreement.
 *
 * Safe to re-run. A site that still has the short "more detail is on the
 * way" page at /industries/salons-and-spas has its sections replaced; a page
 * edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/71-salons.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/salons-and-spas';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;
$catalog = function (string $type): string {
  $tid = ib_term_id('device_type', $type);
  return $tid ? "/hardware?type[$tid]=$tid" : '/hardware';
};
$setup = fn(string $title, string $body, string $note, string $photo, string $link, string $url) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'note' => $note, 'media_label' => $photo, 'link' => ib_link($url, $link)];
$recurring = ib_node_by_alias('/solutions/recurring-billing');

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Salons and spas',
    'heading' => 'Book, check out and tip in one tap.',
    'accent_word' => 'tip',
    'intro' => 'Card on file for no-shows, easy tips for stylists and a small countertop device that fits a crowded front desk. One local team for your hardware, pricing and support.',
    'link' => ib_link('/get-a-quote', 'Get a salon quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
    'checklist' => ['Card on file for no-shows', 'Tips tracked by stylist', 'Support from Austin'],
    'media_label' => 'a client tapping a card at a salon front desk',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Sound familiar?',
    'heading' => 'What salon owners tell us first',
    'items' => [
      $card('calendar', '“No-shows cost me a chair.”', 'Keep a card on file, with the client\'s agreement, and charge the no-show fee your policy sets.'),
      $card('users', '“Tips are a mess at payday.”', 'Clients add a tip on screen, and each stylist\'s tips are tracked under their own login.'),
      $card('store', '“The front desk is tiny.”', 'A compact countertop device takes every way to pay without crowding the products and the appointment book.'),
      $card('repeat', '“Clients want packages and memberships.”', 'Recurring billing charges members on schedule and updates expired cards for you.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Ways to get paid',
    'heading' => 'At the desk, at the chair and every month',
    'intro' => 'Most salons and spas use two of these. They run on one merchant account, with one set of deposits.',
    'items' => [
      $setup('At the front desk', 'A small countertop device for services, tips and the retail products on your shelf.', 'Good for: salons, barbers, nail studios', 'a compact card terminal at a salon front desk', 'See countertop terminals', $catalog('Countertop terminal')),
      $setup('At the chair', 'A handheld so clients can pay and tip without walking to the desk.', 'Good for: spas, busy salons, suites', 'a stylist taking payment at the chair with a handheld', 'See handhelds', $catalog('Handheld')),
      $setup('Every month', 'Memberships and packages that charge on schedule, with expired cards updated for you.', 'Good for: memberships, facials, lash fills', 'a spa client relaxing in a treatment room', 'See recurring billing', '/solutions/recurring-billing'),
    ],
  ]],
  ['bundle', [
    'eyebrow' => 'Recommended setup',
    'heading' => 'A setup for a four-chair salon',
    'intro' => 'A starting point we tune to your space, your services and your volume. Swap any piece for something that fits better.',
    'anchor' => 'setup',
    'items' => array_values(array_filter([
      ($compact = $node_ref('/hardware/clover-compact')) ? ['_type' => 'bundle_item', 'label' => 'Front desk', 'reference' => $compact, 'note' => 'Services, tips and retail on one small device', 'quantity' => 1] : NULL,
      ($flex = $node_ref('/hardware/clover-flex')) ? ['_type' => 'bundle_item', 'label' => 'At the chair', 'reference' => $flex, 'note' => 'Checkout and tip without leaving the chair', 'quantity' => 1] : NULL,
      $recurring ? ['_type' => 'bundle_item', 'label' => 'Memberships', 'reference' => ['target_id' => $recurring->id()], 'note' => 'Packages and memberships that charge on schedule', 'quantity' => 1] : NULL,
    ])),
    'addons' => ['card on file', 'gift cards'],
    'note' => 'Processing and Clover software are quoted separately. We compare flat-rate and dual pricing on your statement.',
    'link' => ib_link('/get-a-quote', 'Quote this setup'),
  ]],
  ['media_split', [
    'eyebrow' => 'Checkout',
    'heading' => 'A calmer end to every appointment',
    'body' => 'The last minute of a visit is the one clients remember. Checkout should be quick, clear and easy to tip on.',
    'checklist' => [
      'Clients add a tip on screen before they tap',
      'Services and retail products rung up together',
      'A card saved on file, with permission, for no-shows and packages',
      'Receipts printed, texted or emailed, whatever the client prefers',
    ],
    'link' => ib_link('/solutions/in-person', 'See in-person payments'),
    'media_label' => 'a stylist and client finishing checkout at the desk',
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Devices salons choose most',
    'intro' => 'Every device ships programmed to your account and ready for your first client.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['clover-compact', 'clover-mini', 'clover-flex', 'clover-go']))),
  ]],
  ['faq', [
    'heading' => 'Salon and spa questions',
    'layout' => 'centered',
    'items' => [
      $faq('Can I charge clients for no-shows?', 'Yes, with a card on file and a clear cancellation policy the client agrees to when they book. Card networks expect that agreement, so we help you word it and set it up.'),
      $faq('How are tips handled for each stylist?', 'Each stylist logs in to take payments, so tips are recorded under their name in your reports. You pay tips out through your own payroll.'),
      $faq('Does it work with my booking app?', 'Often. Many salon booking apps connect to a payment gateway. Tell us which one you use and we will check before you switch.'),
      $faq('Can I sell memberships and packages?', 'Yes. Recurring billing charges members on the schedule you set and updates expired cards, so payments do not quietly stop.'),
      $faq('Which pricing is best for a salon?', 'Flat rate is the easiest to budget, and dual pricing can cost less if your clients are comfortable seeing two prices. We compare both on your real statement.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['customer-disputes-a-charge', 'boutique-switching-to-dual-pricing', 'funding-times-explained']))),
  ]],
  ['cta_band', [
    'heading' => "Let's make checkout the easy part.",
    'accent_word' => 'easy',
    'body' => 'Tell us about your chairs, your services and how you book. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a salon quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
  ]],
];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No salons page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  if ($node->hasField('field_meta_description')) {
    $node->set('field_meta_description', 'Card payments for Austin salons and spas: card on file for no-shows, tips by stylist, memberships and a compact front desk device.');
  }
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "Salons page rebuilt from its short version.\n";
}
else {
  print "Salons page already built; left as it is.\n";
}
