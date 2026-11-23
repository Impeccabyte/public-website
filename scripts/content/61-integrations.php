<?php

/**
 * @file
 * The Integrations solution page, as designed and approved on 2026-11-19.
 *
 * Every platform named has a source; see
 * scripts/content/assets/integration-logos/README.md and
 * docs/maverick-offerings.md. DoorDash, Uber Eats and Grubhub connect to
 * Clover's restaurant plans; carts through Maverick's checkout partners
 * (OpenPath and Checkout Champ for Shopify) and the Valor and iPOSPays
 * plugins; accounting through Chargezoom, Biller Genie and RECUR360. No
 * prices.
 *
 * Safe to re-run. A new site gets the page; a site that still has the short
 * "more detail is on the way" page at /solutions/integrations has its
 * sections replaced; a page edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/61-integrations.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/solutions/integrations';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$jump = fn(string $icon, string $title, string $body, string $anchor, string $label) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body, 'link' => ib_link($path . '#' . $anchor, $label)];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;

// Platform logos as image media. Brand => [file, width, height]; sizes are
// the artwork's own proportions.
$logos = [
  'DoorDash' => ['doordash.svg', 154, 18],
  'Uber Eats' => ['uber-eats.svg', 1052, 170],
  'Grubhub' => ['grubhub.svg', 371, 78],
  'Shopify' => ['shopify.svg', 612, 192],
  'WooCommerce' => ['woocommerce.svg', 184, 48],
  'BigCommerce' => ['bigcommerce.svg', 132, 30],
  'Magento' => ['magento.svg', 179, 60],
  'QuickBooks' => ['quickbooks.svg', 270, 69],
  'Xero' => ['xero.svg', 208, 208],
];
$file_system = \Drupal::service('file_system');
$directory = 'public://integration-logos';
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
$media_storage = \Drupal::entityTypeManager()->getStorage('media');
$media = [];
foreach ($logos as $brand => [$filename, $width, $height]) {
  $name = 'Integration logo: ' . $brand;
  $existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
  if (!($item = reset($existing))) {
    $uri = $file_system->saveData(file_get_contents(__DIR__ . '/assets/integration-logos/' . $filename), $directory . '/' . $filename, FileExists::Replace);
    $file = File::create(['uri' => $uri, 'status' => 1, 'uid' => 1]);
    $file->save();
    $item = Media::create(['bundle' => 'image', 'name' => $name, 'uid' => 1, 'status' => 1, 'field_media_image' => ['target_id' => $file->id(), 'alt' => $brand, 'width' => $width, 'height' => $height]]);
    $item->save();
  }
  $media[$brand] = ['target_id' => $item->id()];
}
$pick = fn(array $brands) => array_map(fn(string $brand) => $media[$brand], $brands);

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Integrations',
    'heading' => 'Payments that talk to your other tools.',
    'accent_word' => 'talk',
    'intro' => "Delivery apps, your online store, your accounting and the POS you already run. Tell us what you use and we'll confirm what connects, and how, before you switch.",
    'link' => ib_link('/get-a-quote', 'Check my tools'),
    'link_secondary' => ib_link($path . '#kinds', 'See what connects'),
    'checklist' => ['Delivery orders on your Clover', 'Plugins for major carts', 'Checked before you switch'],
    'media_label' => 'a busy counter with delivery tickets printing next to the POS',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'What connects',
    'heading' => 'Four kinds of connections',
    'intro' => 'Most businesses need one or two. Pick yours to jump to the details.',
    'anchor' => 'kinds',
    'items' => [
      $jump('truck', 'Delivery apps', 'DoorDash, Uber Eats and Grubhub orders on the same screen as your in-store sales.', 'delivery', 'Delivery and online ordering'),
      $jump('store', 'Online stores', 'Checkout plugins for Shopify, WooCommerce, BigCommerce and Magento.', 'online-store', 'Online stores'),
      $jump('file-text', 'Accounting', 'Sales, fees and invoices synced to QuickBooks, Xero or FreshBooks.', 'accounting', 'Accounting and software'),
      $jump('monitor', 'Your current POS', 'Keep your register software and add a terminal that works alongside it.', 'your-pos', 'Keep your POS'),
    ],
  ]],
  ['logo_strip', [
    'layout' => 'tiles',
    'anchor' => 'delivery',
    'eyebrow' => 'Delivery and online ordering',
    'heading' => 'Delivery orders, straight to your Clover',
    'body' => "On Clover's restaurant plans, DoorDash, Uber Eats and Grubhub connect straight to your POS, so delivery orders land on the same screen and print in the kitchen next to everything else. You can retire the stack of tablets.",
    'logos' => $pick(['DoorDash', 'Uber Eats', 'Grubhub']),
    'title' => 'Using another service?',
    'note' => "Tell us which one and we'll check how it connects.",
    'footnote' => 'Delivery services charge their own fees. Names and logos belong to their owners.',
  ]],
  ['logo_strip', [
    'layout' => 'tiles',
    'anchor' => 'online-store',
    'eyebrow' => 'Online stores',
    'heading' => 'Checkout plugins for the carts you know',
    'body' => 'Maverick works with checkout partners and gateways that have ready-made plugins for the big shopping carts, such as OpenPath and Checkout Champ for Shopify, and Valor and iPOSPays for WooCommerce. We match the plugin to your cart, connect it to your merchant account and run a test order with you.',
    'logos' => $pick(['Shopify', 'WooCommerce', 'BigCommerce', 'Magento']),
    'title' => 'Built on something else?',
    'note' => "Some site builders only take their own payments. Tell us your cart and we'll check.",
    'footnote' => "Plugin availability depends on the gateway and your cart's plan. Names and logos belong to their owners.",
  ]],
  ['logo_strip', [
    'layout' => 'tiles',
    'anchor' => 'accounting',
    'eyebrow' => 'Accounting and business software',
    'heading' => 'Sales that land in your books on their own',
    'body' => 'Connect your payments to your accounting and invoicing software through apps built for Maverick accounts, such as Chargezoom, Biller Genie and RECUR360, so payments, fees and invoices match up without retyping.',
    'logos' => $pick(['QuickBooks', 'Xero']),
    'title' => 'Using an ERP?',
    'note' => "Tell us which one and we'll check whether it connects.",
    'footnote' => 'Some connections use a separate app with its own monthly fee. We list it in your quote. Names and logos belong to their owners.',
  ]],
  ['media_split', [
    'eyebrow' => 'Your current POS',
    'heading' => 'Keep the POS you already have',
    'body' => 'Happy with your register software? If it supports a semi-integrated terminal, we can connect one to it instead of replacing everything.',
    'checklist' => [
      'Your POS rings the sale; the terminal takes the card',
      'Card numbers never pass through your POS',
      'We check compatibility with your POS before you switch',
    ],
    'link' => ib_link('/hardware', 'See countertop terminals'),
    'media_label' => 'an existing POS screen with a card terminal beside it',
    'anchor' => 'your-pos',
  ]],
  ['steps', [
    'eyebrow' => 'How we check',
    'heading' => 'No surprises after you switch',
    'items' => [
      $step('Tell us your tools', 'Your POS, website cart, delivery apps, accounting and anything else that touches a sale.'),
      $step('We check each connection', 'Native, through an app, through the gateway, or not at all. You get the answer in writing.'),
      $step('Your quote lists every fee', 'Including any app or plugin with its own monthly charge, so nothing shows up later.'),
      $step('We set it up and test it', 'We connect each tool and run a test sale through it with you before you go live.'),
    ],
  ]],
  ['faq', [
    'heading' => 'Integration questions',
    'layout' => 'centered',
    'items' => [
      $faq('Will DoorDash orders print in my kitchen?', 'Yes, with Clover. DoorDash connects directly to Clover, so orders show on your POS and print to your kitchen printer like any other ticket. DoorDash handles the setup on its side, usually within a day.'),
      $faq('What about Uber Eats and Grubhub?', "They connect to Clover too. Clover's restaurant plans include Uber Eats, DoorDash and Grubhub, so orders from each show on your POS. Each delivery service charges its own fees on its orders."),
      $faq('Can I use my merchant account with Shopify?', "Yes, through a checkout partner Maverick works with, such as OpenPath or Checkout Champ. Shopify may charge an extra fee on sales that don't use Shopify Payments, so we compare the total cost with you before you switch."),
      $faq('Does it sync with QuickBooks automatically?', "It can. Partner apps such as Chargezoom, Biller Genie and RECUR360 sync payments to QuickBooks Online and Desktop, and the Valor and iPOSPays portals sync to QuickBooks Online. Tell us which version you use and we'll recommend the simplest route."),
      $faq("What if my software isn't listed?", "Ask us. Maverick works with certified software partners, and many tools connect through a gateway. If something won't connect, we'll tell you before you sign."),
      $faq('Do integrations cost extra?', "Some do. Native connections usually don't, while separate apps and plugins can carry their own fee. Every one is listed in your quote."),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['payment-links-vs-online-store', 'clover-flex-first-tableside-payment', 'merchant-account-checklist']))),
  ]],
  ['cta_band', [
    'heading' => "Tell us your tools. We'll connect them.",
    'accent_word' => 'connect',
    'body' => 'List your POS, cart, delivery apps and accounting software in your quote request. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Check my tools'),
    'link_secondary' => ib_link('/solutions/online', 'See online payments'),
  ]],
];

$meta = 'Connect payments to DoorDash, Shopify, WooCommerce, QuickBooks and the POS you already use. We confirm every connection before you switch.';
$node = ib_node_by_alias($path);
if (!$node) {
  ib_page('solution', 'Integrations', $path, $sections, [
    'field_category' => ['target_id' => ib_term_id('solution_category', 'Run Your Business')],
    'field_short_description' => 'Connect invoicing, accounting and your POS',
    'field_icon' => 'plug',
    'field_meta_description' => $meta,
  ]);
  print "Integrations page created.\n";
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
  print "Integrations page rebuilt from its short version.\n";
}
else {
  print "Integrations page already built; left as it is.\n";
}
