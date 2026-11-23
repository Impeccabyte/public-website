<?php

/**
 * @file
 * The Funding solution page, as designed and approved on 2026-11-19.
 *
 * Explains batching, settlement and what moves a deposit date. No funding
 * times beyond "cutoffs vary by processor" (Ry, 2026-11-18). Impeccabyte is
 * a sales agent and never holds merchant funds, so nothing here suggests it
 * pays deposits.
 *
 * Safe to re-run. A new site gets the page; a site that still has the short
 * "more detail is on the way" page at /solutions/funding has its sections
 * replaced; a page edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/63-funding.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/solutions/funding';
$guide = '/resources/funding-times-explained';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Funding',
    'heading' => 'Know when your money arrives.',
    'accent_word' => 'when',
    'intro' => "We set up your deposits, show you how batching works and walk you through your processor's cutoff, so the timing makes sense from your first day. Cutoffs vary by processor.",
    'link' => ib_link('/get-a-quote', 'Get a quote'),
    'link_secondary' => ib_link($guide, 'Read the funding guide'),
    'checklist' => ['Deposits to your business bank account', 'Your cutoff explained up front', 'Support from Austin'],
    'media_label' => 'an owner checking deposits after closing',
    'show_breadcrumb' => 1,
  ]],
  ['steps', [
    'eyebrow' => 'How funding works',
    'heading' => 'From a tap to your bank account',
    'intro' => 'Every card sale goes through the same four steps. Knowing them makes the timing of each deposit easy to follow.',
    'items' => [
      $step('Your customer pays', 'The card is approved and the sale is recorded on your terminal or gateway.'),
      $step('You close the batch', "At the end of the day, the day's sales are grouped into one batch. Most terminals can close it for you at a set time."),
      $step('The batch settles', "The processor sends the batch to the card networks, which move the money from each customer's bank."),
      $step('The deposit lands', 'The total, less any refunds and fees, is deposited to your business bank account. Cutoffs vary by processor.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Timing',
    'heading' => 'What decides when a deposit lands',
    'intro' => 'Four things move the date. We go through each one with you when we set up your account.',
    'items' => [
      $card('clock', 'When you close', "A batch closed before your processor's cutoff moves sooner than one closed after it."),
      $card('calendar', 'Weekends and holidays', "Banks don't move money on weekends or bank holidays, so Friday and Saturday sales can wait."),
      $card('landmark', 'Your own bank', 'Some banks post incoming deposits later in the day than others.'),
      $card('undo-2', 'Refunds and disputes', 'Refunds and chargebacks come out of your deposits, so a busy refund day means a smaller one.'),
    ],
  ]],
  ['media_split', [
    'eyebrow' => 'Reconciling',
    'heading' => 'Every deposit, matched to its sales',
    'body' => 'A deposit is a batch of sales, less any refunds and fees. Your reports show which sales make up each one, so your books line up with your bank.',
    'checklist' => [
      'Each deposit traced back to the batch it came from',
      'Tips adjusted before close are paid with that batch',
      'We explain whether fees come out daily or once a month',
    ],
    'link' => ib_link('/resources/read-a-processing-statement', 'Read the statement guide'),
    'media_label' => 'an owner matching deposits to her bank statement',
  ]],
  ['faq', [
    'heading' => 'Funding questions',
    'layout' => 'centered',
    'items' => [
      $faq('When will I get paid?', "It depends on when you close your batch, your processor's cutoff, weekends and holidays, and your bank. Cutoffs vary by processor, and we tell you yours when we set up your account."),
      $faq("Why didn't a deposit arrive over the weekend?", "Banks don't move money on weekends or bank holidays. Sales from those days are deposited on the next business day the banks are open."),
      $faq('Why is my deposit less than my sales?', "A deposit is your sales less any refunds, chargebacks and, on some accounts, daily fees. Your reports show each part, and we'll walk you through your first few."),
      $faq('Can I change when my batch closes?', "Usually, yes. Most terminals let you set an automatic close time, and we'll help you pick one that falls before your processor's cutoff."),
      $faq('Can I change the bank account my deposits go to?', "Yes. For your security, a change needs a written request from the account owner. We'll walk you through it."),
      $faq('Does Impeccabyte hold my money?', 'No. Deposits are paid by Maverick Payments and its sponsor banks directly to your business bank account. We never hold or move your funds.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['funding-times-explained', 'read-a-processing-statement', 'merchant-account-checklist']))),
  ]],
  ['cta_band', [
    'heading' => 'Deposits you can plan around.',
    'accent_word' => 'plan',
    'body' => 'Ask us how funding would work for your business. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a quote'),
    'link_secondary' => ib_link($guide, 'Read the funding guide'),
  ]],
];

$meta = 'How card payments become deposits: batches, cutoffs, weekends and refunds explained. Cutoffs vary by processor. Set up and supported from Austin.';
$node = ib_node_by_alias($path);
if (!$node) {
  ib_page('solution', 'Funding', $path, $sections, [
    'field_category' => ['target_id' => ib_term_id('solution_category', 'Run Your Business')],
    'field_short_description' => 'How and when deposits reach your bank',
    'field_icon' => 'zap',
    'field_meta_description' => $meta,
  ]);
  print "Funding page created.\n";
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
  print "Funding page rebuilt from its short version.\n";
}
else {
  print "Funding page already built; left as it is.\n";
}
