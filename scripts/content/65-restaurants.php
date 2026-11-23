<?php

/**
 * @file
 * Fills out the Restaurants and bars page, 2026-11-19.
 *
 * scripts/content/40-solutions-industries.php creates the page from the
 * wireframe; this script adds a "ways to serve" row of photo cards, the
 * delivery logos from the Integrations page, a dinner-rush section, device
 * cards and a "Keep reading" row. The wireframe's sections are kept as they
 * are, and the case study stays a placeholder until a real one is approved.
 *
 * Claims reuse what the page and the Integrations page already say: split
 * checks on Clover Flex, tips adjusted before the batch closes, Clover's
 * offline mode, delivery apps connecting to Clover. No prices in text.
 *
 * Runs after scripts/content/61-integrations.php, which adds the delivery
 * logos. Safe to re-run: only a page still in its wireframe shape is
 * changed. Run it with
 *   ./scripts/drush php:script scripts/content/65-restaurants.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/restaurants';
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;
$catalog = function (string $type): string {
  $tid = ib_term_id('device_type', $type);
  return $tid ? "/hardware?type[$tid]=$tid" : '/hardware';
};
$setup = fn(string $title, string $body, string $note, string $photo, string $link, string $url) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'note' => $note, 'media_label' => $photo, 'link' => ib_link($url, $link)];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No restaurants page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles !== ['hero', 'feature_grid', 'bundle', 'case_study', 'faq', 'cta_band']) {
  print "Restaurants page already filled out or edited; left as it is.\n";
  return;
}
[$hero, $pains, $bundle, $story, $faq, $cta] = $existing;

// Delivery logos come from the Integrations page.
$logos = [];
foreach (['DoorDash', 'Uber Eats', 'Grubhub'] as $brand) {
  $found = \Drupal::entityTypeManager()->getStorage('media')->loadByProperties(['bundle' => 'image', 'name' => 'Integration logo: ' . $brand]);
  if ($item = reset($found)) {
    $logos[] = ['target_id' => $item->id()];
  }
}

$sections = [
  $hero,
  $pains,
  ib_paragraph('feature_grid', [
    'eyebrow' => 'Ways to serve',
    'heading' => 'Counter, table or kiosk, on one system',
    'intro' => 'Most restaurants use two of these. They all run on the same menu, the same reports and the same deposits.',
    'items' => [
      $setup('At the counter', 'A dual-screen POS where staff ring up orders and guests see their total and add a tip.', 'Good for: quick service, coffee, bars', 'a counter POS with a customer-facing screen', 'See POS systems', $catalog('POS System')),
      $setup('At the table', 'A handheld that takes the order and the payment, prints the receipt and splits the check.', 'Good for: full service, patios, curbside', 'a server taking payment at the table', 'See handhelds', $catalog('Handheld')),
      $setup('At a kiosk', 'A self-order screen that takes orders and payment on its own, so the line keeps moving.', 'Good for: fast casual, lunch rush', 'a guest ordering at a self-order kiosk', 'See self-order kiosks', $catalog('Self-Order Kiosk')),
    ],
  ]),
  $bundle,
  ib_paragraph('logo_strip', [
    'layout' => 'tiles',
    'anchor' => 'delivery',
    'eyebrow' => 'Delivery',
    'heading' => 'Delivery orders on the same screen',
    'body' => "DoorDash, Uber Eats and Grubhub connect to Clover's restaurant plans, so delivery orders print in your kitchen with everything else.",
    'logos' => $logos,
    'title' => 'More on integrations',
    'note' => 'Online ordering, accounting and other tools are on our integrations page.',
    'footnote' => 'Delivery services charge their own fees. Names and logos belong to their owners.',
  ]),
  ib_paragraph('media_split', [
    'eyebrow' => 'Service',
    'heading' => 'Built for the dinner rush',
    'body' => 'The busiest hour is when payments matter most. Every piece of the setup is chosen to keep tables turning and the kitchen in sync.',
    'checklist' => [
      'Orders fire to a kitchen display, in order, with timers',
      'Split checks by item, by seat or evenly, right at the table',
      'Adjust tips before the batch closes, with no paper slips',
      'Clover devices keep taking payments offline for a period if the internet drops',
    ],
    'link' => ib_link('/solutions/integrations', 'See integrations'),
    'media_label' => 'a busy kitchen pass with a kitchen display',
  ]),
  ib_paragraph('card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Devices restaurants choose most',
    'intro' => 'Every device ships programmed to your menu and ready for service.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['clover-station-duo', 'clover-flex', 'clover-kiosk', 'pax-a920-pro']))),
  ]),
  $story,
  $faq,
  ib_paragraph('related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['clover-flex-first-tableside-payment', 'boutique-switching-to-dual-pricing', 'funding-times-explained']))),
  ]),
  $cta,
];

$node->set('field_sections', $sections);
$node->save();
print "Restaurants page filled out.\n";
