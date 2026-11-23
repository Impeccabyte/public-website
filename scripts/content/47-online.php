<?php

/**
 * @file
 * The Online solution page, as designed and approved on 2026-11-19.
 *
 * Capabilities named (virtual terminal for card and ACH, hosted forms,
 * tokenization, account updater, fraud and chargeback tools) come from
 * Maverick's online payments and gateway pages. Gateways named support TSYS
 * or Fiserv, per Ry on 2026-11-19; see scripts/content/assets/gateway-logos.
 * No prices, no funding times.
 *
 * Safe to re-run. A new site gets the page; a site that still has the short
 * "more detail is on the way" page at /solutions/online has its sections
 * replaced; a page edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/47-online.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/solutions/online';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;
$setup = fn(string $title, string $body, string $note, string $photo, string $link) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'note' => $note, 'media_label' => $photo, 'link' => ib_link('/get-a-quote', $link)];

// Gateway logos as image media.
$logos = [
  'Authorize.Net' => ['authorize-net.png', 206, 45],
  'NMI' => ['nmi.png', 838, 270],
  'accept.blue' => ['accept-blue.png', 1570, 302],
];
$file_system = \Drupal::service('file_system');
$directory = 'public://gateway-logos';
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
$media_storage = \Drupal::entityTypeManager()->getStorage('media');
$logo_items = [];
foreach ($logos as $brand => [$filename, $width, $height]) {
  $name = 'Gateway logo: ' . $brand;
  $existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
  if (!($item = reset($existing))) {
    $uri = $file_system->saveData(file_get_contents(__DIR__ . '/assets/gateway-logos/' . $filename), $directory . '/' . $filename, FileExists::Replace);
    $file = File::create(['uri' => $uri, 'status' => 1, 'uid' => 1]);
    $file->save();
    $item = Media::create(['bundle' => 'image', 'name' => $name, 'uid' => 1, 'status' => 1, 'field_media_image' => ['target_id' => $file->id(), 'alt' => $brand, 'width' => $width, 'height' => $height]]);
    $item->save();
  }
  $logo_items[] = ['target_id' => $item->id()];
}

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Online payments',
    'heading' => 'Get paid online without rebuilding your site.',
    'accent_word' => 'online',
    'intro' => 'Payment links, hosted checkout and a virtual terminal that drop into the way you already sell. Already have a gateway? In many cases you can keep it.',
    'link' => ib_link('/get-a-quote', 'Get an online quote'),
    'link_secondary' => ib_link($path . '#gateways', 'See gateways we work with'),
    'checklist' => ['Card and ACH', 'No website needed to start', 'Support from Austin'],
    'media_label' => 'an owner packing an online order beside a laptop',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'The tools',
    'heading' => "Every way to take a payment that isn't in person",
    'intro' => 'Use one, or all four. They run on the same merchant account, so your money and reporting stay in one place.',
    'items' => [
      $card('link', 'Payment links', 'Send a link by text or email and get paid from any phone. Good for deposits, orders by message and one-off sales.'),
      $card('globe', 'Hosted checkout', 'A secure payment page that matches your logo and connects to your website or shopping cart.'),
      $card('monitor', 'Virtual terminal', 'Key in card or ACH payments from any browser, for phone orders and invoices paid over the phone.'),
      $card('repeat', 'Recurring billing', 'Memberships, subscriptions and payment plans that charge on schedule and update expired cards for you.'),
    ],
  ]],
  ['logo_strip', [
    'layout' => 'tiles',
    'anchor' => 'gateways',
    'eyebrow' => 'Gateways',
    'heading' => 'Our gateway, or the one you prefer',
    'body' => "We set you up on Maverick's own gateway as standard. If you'd rather use a popular gateway, or already have one, we can set that up too. Gateways that run on TSYS or Fiserv, the network behind Clover, connect to your merchant account, so switching who processes your cards doesn't mean rebuilding your checkout.",
    'logos' => $logo_items,
    'names' => array_keys($logos),
    'title' => 'Using something else?',
    'note' => "Most gateways that support TSYS or Fiserv will work. Tell us which one and we'll check.",
    'footnote' => "Maverick's gateway comes standard, with a virtual terminal, hosted payment forms, recurring billing and a card account updater. Gateway names and logos belong to their owners.",
  ]],
  ['feature_grid', [
    'eyebrow' => 'Pick your setup',
    'heading' => 'Three ways to start selling online',
    'intro' => 'Start with what you have today. You can add the others later without opening a new account.',
    'items' => [
      $setup('Payment links and invoices', 'No website needed. Send a link, get paid, see it in your reporting the same day.', 'Good for: services, deposits, made-to-order', 'an owner sending a payment link from a phone', 'Start with payment links'),
      $setup('Checkout on your website', 'Add a hosted payment page or plug a gateway into your shopping cart. We help you connect it.', 'Good for: online shops, booking sites', 'a laptop showing an online shop checkout', 'Add checkout to your site'),
      $setup('A gateway for your software', 'Keep your current gateway, or connect your platform or developer to one, with an API and test account.', 'Good for: custom sites, software, high volume', 'a developer at a desk with two screens', 'Talk about your setup'),
    ],
  ]],
  ['steps', [
    'eyebrow' => 'How it works',
    'heading' => 'From a quote to your first online sale',
    'items' => [
      $step('Tell us how you sell online', 'Your website, your cart, your gateway and roughly what you sell each month.'),
      $step('Get a quote in writing', 'We recommend a setup and a pricing program, and list every fee, gateway included, before you sign.'),
      $step('We connect your gateway', 'We set up a new gateway or point your current one at your merchant account, and send test details.'),
      $step('Take your first online payment', 'Run a test sale with us, then go live. We stay your local point of contact.'),
    ],
  ]],
  ['media_split', [
    'eyebrow' => 'Security',
    'heading' => 'Card details stay out of your hands',
    'body' => 'The gateway handles the sensitive part, so card numbers never sit on your website or in your inbox.',
    'checklist' => [
      "Hosted payment forms take the card on the gateway's page, not yours",
      'Saved cards are stored as tokens, so repeat customers check out faster',
      'Fraud and chargeback tools flag risky orders before you ship',
    ],
    'link' => ib_link('/solutions/chargeback-protection', 'See chargeback protection'),
    'media_label' => 'a customer paying on a phone at home',
  ]],
  ['callout', [
    'callout_variant' => 'brand',
    'title' => 'Online sales are priced too, so compare before you sign.',
    'text' => ib_html('<p>Card-not-present sales cost a little more to process than tapped ones. We put the gateway fee and the processing rate in writing, and work out which pricing program costs you least from your real statement. <a href="/pricing">See how the three compare</a>.</p>'),
  ]],
  ['faq', [
    'heading' => 'Online payment questions',
    'layout' => 'centered',
    'items' => [
      $faq('Do I need a website to take payments online?', 'No. Payment links and invoices work by text or email, and the virtual terminal works from any browser. You can add website checkout later on the same account.'),
      $faq("Can I use a gateway other than Maverick's?", "Yes. Maverick's gateway comes standard, and we can also set up a popular gateway such as Authorize.Net, NMI or accept.blue, whether it is new to you or one you already use. Gateways that support TSYS or Fiserv can usually be connected to your merchant account; tell us which one and we will confirm before you switch."),
      $faq('Can I take ACH payments as well as cards?', 'Yes. The virtual terminal takes card and ACH payments, which can cost less for large invoices. Ask us what fits your business.'),
      $faq('Is it secure?', 'Hosted payment forms take the card on the gateway, not your website, and saved cards are stored as tokens. That keeps card numbers out of your systems and makes PCI compliance simpler.'),
      $faq('How fast will I get my money?', 'Deposit timing depends on your account and on when you close your batch each day. Funding cutoffs vary by processor, and we walk you through yours when we set you up.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['payment-links-vs-online-store', 'customer-disputes-a-charge', 'interchange-assessments-markup']))),
  ]],
  ['cta_band', [
    'heading' => "Tell us how you sell online. We'll connect the rest.",
    'accent_word' => 'connect',
    'body' => 'Tell us your website, your cart and your gateway. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get an online quote'),
    'link_secondary' => ib_link($path . '#gateways', 'See gateways'),
  ]],
];

$meta = 'Payment links, hosted checkout and a virtual terminal on Maverick’s gateway, or a popular gateway you prefer. Set up and supported from Austin.';
$node = ib_node_by_alias($path);
if (!$node) {
  ib_page('solution', 'Online', $path, $sections, [
    'field_category' => ['target_id' => ib_term_id('solution_category', 'Accept Payments')],
    'field_short_description' => 'Gateways, hosted checkout and payment links',
    'field_icon' => 'globe',
    'field_meta_description' => $meta,
  ]);
  print "Online page created.\n";
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
  print "Online page rebuilt from its short version.\n";
}
else {
  print "Online page already built; left as it is.\n";
}
