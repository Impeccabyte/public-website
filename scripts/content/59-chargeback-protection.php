<?php

/**
 * @file
 * The Chargeback protection solution page, as designed and approved on
 * 2026-11-19.
 *
 * Tools named come from Maverick's chargeback management and fraud
 * prevention pages (maverickpayments.com/chargeback-management and
 * /fraud-prevention): email and SMS alerts, drag-and-drop evidence, reusable
 * documents, ratio and reason-code reports, AVS, CVV and BIN checks,
 * 3D Secure, negative chargeback database, order insight and rapid dispute
 * resolution. No fees, no thresholds, no deadlines in days.
 *
 * Safe to re-run. A new site gets the page; a site that still has the short
 * "more detail is on the way" page at /solutions/chargeback-protection has
 * its sections replaced; a page edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/59-chargeback-protection.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/solutions/chargeback-protection';
$guide = '/resources/customer-disputes-a-charge';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Chargeback protection',
    'heading' => 'Catch disputes before they cost you.',
    'accent_word' => 'before',
    'intro' => 'Fraud checks that stop risky sales, an alert the moment a dispute is opened, and a local team that helps you answer it with the right evidence.',
    'link' => ib_link('/get-a-quote', 'Get a quote'),
    'link_secondary' => ib_link($guide, 'Read the dispute guide'),
    'checklist' => ['Email and text alerts', 'Fraud checks built in', 'Help from Austin'],
    'media_label' => 'a shop owner checking a dispute alert on her phone',
    'show_breadcrumb' => 1,
  ]],
  ['steps', [
    'eyebrow' => 'How a chargeback works',
    'heading' => 'What happens when a customer disputes a charge',
    'intro' => 'The money is pulled back while the bank decides. The faster you answer, the better your chances of getting it back.',
    'items' => [
      $step('A customer disputes a charge', "They call their bank about a sale they don't recognize, didn't receive or aren't happy with."),
      $step('You get an alert', "An email or text tells you a chargeback has been opened, and the amount is held while it's reviewed."),
      $step('You send your evidence', 'Receipts, delivery proof, signed terms or messages, uploaded in your dashboard before the deadline.'),
      $step('The bank decides', 'If the evidence shows the sale was valid, the money comes back to you.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Prevention',
    'heading' => 'Stop disputes before they start',
    'intro' => "Most chargebacks come from fraud or a customer who doesn't recognize a charge. These checks catch both.",
    'items' => [
      $card('credit-card', 'Address and code checks', "Each card's billing ZIP and security code are checked, along with the card's issuing bank and type."),
      $card('lock', '3D Secure for online sales', 'An extra step where the cardholder confirms the purchase with their bank, for riskier online orders.'),
      $card('triangle-alert', 'Repeat disputer alerts', 'A warning when a card that disputed you before is used again, so you can void the sale.'),
      $card('receipt', 'Clear details for the bank', 'Purchase details shared with the card issuer, so customers recognize the charge instead of disputing it.'),
    ],
  ]],
  ['media_split', [
    'eyebrow' => 'When a dispute comes in',
    'heading' => 'Answer it in minutes, not afternoons',
    'body' => 'Everything happens in your merchant dashboard, and we help you decide what evidence to send.',
    'checklist' => [
      'An email or text alert as soon as a chargeback is opened',
      'Drag-and-drop upload for receipts, photos and messages',
      'Save your terms and refund policy once and reuse them',
      'Reports on your chargeback ratio, reasons and reversals',
    ],
    'link' => ib_link($guide, 'Read the dispute guide'),
    'media_label' => 'an owner pulling together receipts on a laptop',
  ]],
  ['callout', [
    'callout_variant' => 'warning',
    'title' => 'Keep an eye on your chargeback ratio.',
    'text' => ib_html("<p>Card networks watch the share of your sales that turn into chargebacks. If it climbs too high, they can add monitoring and fees to your account. Your dashboard shows your ratio, and we'll reach out if it starts to rise.</p>"),
  ]],
  ['faq', [
    'heading' => 'Chargeback questions',
    'layout' => 'centered',
    'items' => [
      $faq('What is a chargeback?', 'A chargeback happens when a customer asks their bank to reverse a card payment. The bank pulls the money back from your account while it reviews the dispute, and you can send evidence that the sale was valid.'),
      $faq('How long do I have to respond?', 'Card networks set the deadline, and it is short. Answer as soon as the alert arrives; the dashboard shows each open chargeback so nothing slips.'),
      $faq('Will you respond for me?', 'You send your response through the dashboard, and we help you decide what to include. For a tricky dispute, call us and we will walk through it with you.'),
      $faq('What is friendly fraud?', "It is when a real customer disputes a real purchase, often because they don't recognize the name on their statement or forgot the order. A clear billing name and clear purchase details prevent most of these."),
      $faq('Can some disputes be settled automatically?', 'Yes. Rapid dispute resolution can refund certain disputes automatically under rules you set, before they become a chargeback that counts against you.'),
      $faq('Does chargeback protection cost extra?', 'Alerts and dashboard tools come with your account. Any chargeback fees or add-on tools are listed in your quote, line by line.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['customer-disputes-a-charge', 'payment-links-vs-online-store', 'read-a-processing-statement']))),
  ]],
  ['cta_band', [
    'heading' => 'Keep more of what you earn.',
    'accent_word' => 'earn',
    'body' => "Dealing with disputes now? Tell us what's happening and a local specialist will reply within one business day.",
    'link' => ib_link('/get-a-quote', 'Get a quote'),
    'link_secondary' => ib_link($guide, 'Read the dispute guide'),
  ]],
];

$meta = 'Chargeback alerts, fraud checks and dispute help from a local Austin team, so disputes cost you less and happen less often.';
$node = ib_node_by_alias($path);
if (!$node) {
  ib_page('solution', 'Chargeback protection', $path, $sections, [
    'field_category' => ['target_id' => ib_term_id('solution_category', 'Lower your costs')],
    'field_short_description' => 'Alerts and dispute help before losses stack up',
    'field_icon' => 'shield-check',
    'field_meta_description' => $meta,
  ]);
  print "Chargeback protection page created.\n";
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
  print "Chargeback protection page rebuilt from its short version.\n";
}
else {
  print "Chargeback protection page already built; left as it is.\n";
}
