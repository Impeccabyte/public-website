<?php

/**
 * @file
 * The Recurring billing solution page, as designed and approved on 2026-11-19.
 *
 * Recurring billing, ACH and the card account updater come from Maverick's
 * gateway pages (see scripts/content/47-online.php). No prices, no funding
 * times. Runs after scripts/content/50-resources.php so the "Keep reading"
 * row can point at the library.
 *
 * Safe to re-run. A new site gets the page; a site that still has the short
 * "more detail is on the way" page at /solutions/recurring-billing has its
 * sections replaced; a page edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/55-recurring-billing.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/solutions/recurring-billing';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;
$setup = fn(string $title, string $body, string $note, string $photo, string $link) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'note' => $note, 'media_label' => $photo, 'link' => ib_link('/get-a-quote', $link)];

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Recurring billing',
    'heading' => 'Bill customers on a schedule.',
    'accent_word' => 'schedule',
    'intro' => "Memberships, service plans, payment plans and retainers that charge on their own. Expired cards are updated for you, so payments don't quietly stop.",
    'link' => ib_link('/get-a-quote', 'Get a recurring billing quote'),
    'link_secondary' => ib_link('/pricing', 'See pricing'),
    'checklist' => ['Card and ACH', 'Card account updater', 'Support from Austin'],
    'media_label' => 'a studio owner checking member payments at the front desk',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'What you can set up',
    'heading' => 'Any payment that comes around again',
    'intro' => "Set the amount and the schedule once. The gateway charges the saved card or bank account on the day it's due and emails your customer a receipt.",
    'items' => [
      $card('repeat', 'Memberships and subscriptions', 'Charge the same amount weekly, monthly or yearly until the customer cancels.'),
      $card('clock', 'Payment plans', 'Split a large bill into set payments with an end date, so customers can say yes to bigger work.'),
      $card('receipt', 'Invoices with autopay', 'Send an invoice and let customers save a card or bank account so the next one pays itself.'),
      $card('credit-card', 'Card account updater', 'Expired and reissued cards are refreshed before the next charge, so fewer payments fail.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => "Who it's for",
    'heading' => 'Three common ways businesses use it',
    'intro' => "If your customers pay you more than once, there's probably a schedule that saves you a chase.",
    'items' => [
      $setup('Memberships and classes', 'Monthly dues for gyms, studios and clubs that renew on their own, with one place to see who is current.', 'Good for: fitness, studios, clubs, nonprofits', 'a studio member checking in at the front desk', 'Set up memberships'),
      $setup('Service plans', 'Charge after each visit or on a set day for lawn care, cleaning, pest control and other regular work.', 'Good for: home services, maintenance, subscriptions', 'a lawn care worker finishing a yard', 'Set up service plans'),
      $setup('Payment plans', 'Let customers pay for bigger work in set installments that run themselves, with an end date in writing.', 'Good for: dental, auto repair, tuition, events', 'a front desk explaining a payment plan', 'Set up payment plans'),
    ],
  ]],
  ['steps', [
    'eyebrow' => 'How it works',
    'heading' => 'From a quote to your first automatic payment',
    'items' => [
      $step('Tell us what you bill', 'Your plans or memberships, how often you charge, and roughly how many customers.'),
      $step('Get a quote in writing', 'We recommend a setup and a pricing program and list every fee, gateway included, before you sign.'),
      $step('We set up your plans', 'We build your first plans on the gateway and show you how to add customers and save their cards.'),
      $step('Watch the first charge run', 'Your first scheduled payment runs on its own. We stay your local point of contact.'),
    ],
  ]],
  ['media_split', [
    'eyebrow' => 'Fewer missed payments',
    'heading' => 'Stop chasing expired cards',
    'body' => 'Cards expire, get lost and get reissued. The card account updater looks for new card details before the next charge, so a plan keeps running without a phone call.',
    'checklist' => [
      'Saved cards are stored as tokens on the gateway, not in your files',
      'Change an amount, a date or a card from the gateway dashboard',
      'See every scheduled and declined payment in one report',
    ],
    'link' => ib_link('/solutions/online', 'See online payments'),
    'media_label' => 'a lawn care owner checking payments from the truck',
  ]],
  ['callout', [
    'callout_variant' => 'brand',
    'title' => 'Get a clear yes before the first charge.',
    'text' => ib_html("<p>Card networks expect customers to agree to a recurring charge, know the amount and schedule, and have an easy way to cancel. Clear sign-up terms also head off most disputes. We'll show you what a good sign-up looks like when we set you up.</p>"),
  ]],
  ['faq', [
    'heading' => 'Recurring billing questions',
    'layout' => 'centered',
    'items' => [
      $faq('Can customers pay by bank account instead of card?', 'Yes. Recurring payments can run on a card or an ACH bank account. ACH can cost less for larger amounts, so ask us which fits your plans.'),
      $faq("What happens when a customer's card expires?", 'The card account updater checks for new card details before the next charge. If a payment still declines, it shows in your report so you can follow up.'),
      $faq('Do customers need to give permission for recurring charges?', "Yes. Card networks expect customers to agree to the amount and schedule and to have an easy way to cancel. We'll show you what clear sign-up terms look like."),
      $faq('Does it work with my booking or accounting software?', "Often. Many booking, membership and accounting tools connect to a gateway. Tell us what you use and we'll check before you switch."),
      $faq('How fast will I get my money?', 'Deposit timing depends on your account and on when your batch closes each day. Funding cutoffs vary by processor, and we walk you through yours when we set you up.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['payment-links-vs-online-store', 'customer-disputes-a-charge', 'funding-times-explained']))),
  ]],
  ['cta_band', [
    'heading' => 'Get paid on time, every time.',
    'accent_word' => 'every',
    'body' => 'Tell us what you bill and how often. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a recurring billing quote'),
    'link_secondary' => ib_link('/solutions/online', 'See online payments'),
  ]],
];

$meta = 'Recurring billing for memberships, service plans and payment plans, on card or ACH, with a card account updater. Set up and supported from Austin.';
$node = ib_node_by_alias($path);
if (!$node) {
  ib_page('solution', 'Recurring Billing', $path, $sections, [
    'field_category' => ['target_id' => ib_term_id('solution_category', 'Accept Payments')],
    'field_short_description' => 'Subscriptions, invoices and card updater',
    'field_icon' => 'repeat',
    'field_meta_description' => $meta,
  ]);
  print "Recurring billing page created.\n";
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
  print "Recurring billing page rebuilt from its short version.\n";
}
else {
  print "Recurring billing page already built; left as it is.\n";
}
