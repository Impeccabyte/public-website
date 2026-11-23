<?php

/**
 * @file
 * The Healthcare and dental industry page, 2026-11-19.
 *
 * Built on the Restaurants and bars page's pattern. The sample setup follows
 * the Home page's healthcare chip: a Dejavoo Z8 at the front desk,
 * text-to-pay for balances and recurring payment plans. The page makes no
 * HIPAA or other compliance claims and mentions no health information; card
 * on file is always described with the patient's permission.
 *
 * Safe to re-run. A site that still has the short "more detail is on the
 * way" page at /industries/healthcare has its sections replaced; a page
 * edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/75-healthcare.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/healthcare';
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
    'eyebrow' => 'Healthcare & Dental',
    'heading' => 'Payments that respect the front desk.',
    'accent_word' => 'respect',
    'intro' => 'Card on file, payment plans and text-to-pay for balances, with card storage handled by the gateway. One local team for your setup and support.',
    'link' => ib_link('/get-a-quote', 'Get a practice quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
    'checklist' => ['Text-to-pay for balances', 'Payment plans that run on their own', 'Support from Austin'],
    'media_label' => 'a patient paying at a dental office front desk',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Sound familiar?',
    'heading' => 'What practice managers tell us first',
    'items' => [
      $card('clock', '“Balances sit unpaid for months.”', 'Text a payment link when the balance is known, and patients pay from their phone in a minute.'),
      $card('users', '“The front desk is swamped.”', 'A small countertop terminal takes tap, chip and phone wallets quickly, so check-out keeps moving.'),
      $card('repeat', '“Patients can\'t pay it all at once.”', 'Payment plans charge a set amount on a set day until the balance is paid.'),
      $card('lock', '“We don\'t want card numbers in our system.”', 'Saved cards are stored as tokens by the gateway, not in your files or your practice software.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Ways to get paid',
    'heading' => 'At check-out, after the visit and over time',
    'intro' => 'Most practices use all three. They run on one merchant account, so every payment lands in the same reports.',
    'items' => [
      $setup('At check-out', 'A compact countertop terminal for copays and same-day payments.', 'Good for: dental, chiropractic, therapy, vision', 'a countertop card terminal at a clinic front desk', 'See countertop terminals', $catalog('Countertop Terminal')),
      $setup('After the visit', 'Text or email a payment link for the balance once the amount is known.', 'Good for: balances after insurance, missed copays', 'a patient paying a balance on her phone at home', 'See online payments', '/solutions/online'),
      $setup('Over time', 'Payment plans that charge on schedule, with expired cards updated automatically.', 'Good for: orthodontics, larger treatment plans', 'a treatment coordinator walking a parent through a plan', 'See recurring billing', '/solutions/recurring-billing'),
    ],
  ]],
  ['bundle', [
    'eyebrow' => 'Recommended setup',
    'heading' => 'A setup for a two-chair practice',
    'intro' => 'A starting point we tune to your front desk, your patients and your volume. Swap any piece for something that fits better.',
    'anchor' => 'setup',
    'items' => array_values(array_filter([
      ($z8 = $node_ref('/hardware/dejavoo-z8')) ? ['_type' => 'bundle_item', 'label' => 'Front desk', 'reference' => $z8, 'note' => 'Copays and same-day payments at check-out', 'quantity' => 1] : NULL,
      ($online = $node_ref('/solutions/online')) ? ['_type' => 'bundle_item', 'label' => 'Balances', 'reference' => $online, 'note' => 'Text-to-pay links for balances after the visit', 'quantity' => 1] : NULL,
      ($recurring = $node_ref('/solutions/recurring-billing')) ? ['_type' => 'bundle_item', 'label' => 'Payment plans', 'reference' => $recurring, 'note' => 'Set amounts on set days until the balance is paid', 'quantity' => 1] : NULL,
    ])),
    'addons' => ['card on file', 'ACH payments'],
    'note' => 'Processing and gateway fees are quoted separately.',
    'link' => ib_link('/get-a-quote', 'Quote this setup'),
  ]],
  ['media_split', [
    'eyebrow' => 'Patients',
    'heading' => 'Kind to patients, easy on staff',
    'body' => 'Paying should be the simplest part of a visit. These are the pieces that make it that way.',
    'checklist' => [
      'A card on file, with the patient\'s written permission, for balances after insurance',
      'Payment plans with a set amount and an end date',
      'HSA and FSA cards taken like any other card',
      'Receipts printed, texted or emailed, whatever the patient prefers',
    ],
    'link' => ib_link('/solutions/recurring-billing', 'See payment plans'),
    'media_label' => 'a front desk team member helping a patient at check-out',
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Devices practices choose most',
    'intro' => 'Every device ships programmed to your account and ready for your first patient.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['dejavoo-z8', 'dejavoo-qd4', 'clover-compact', 'clover-mini']))),
  ]],
  ['faq', [
    'heading' => 'Healthcare & Dental questions',
    'layout' => 'centered',
    'items' => [
      $faq('Can we text patients a link to pay a balance?', 'Yes. Send a payment link by text or email once the balance is known, and the patient pays by card from their phone.'),
      $faq('Can patients pay over time?', 'Yes. Set up a payment plan with an amount, a schedule and an end date. It charges on its own and updates expired cards.'),
      $faq('Can we keep a card on file?', 'Yes, with the patient\'s written permission. The card is stored as a token by the gateway, not in your files or practice software.'),
      $faq('Do you accept HSA and FSA cards?', 'Most HSA and FSA cards run on the major card networks and are taken like any other card for eligible services.'),
      $faq('Will it work with our practice management software?', 'Often. Many practice management systems connect to a payment gateway. Tell us which one you use and we will check before you switch.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['payment-links-vs-online-store', 'customer-disputes-a-charge', 'read-a-processing-statement']))),
  ]],
  ['cta_band', [
    'heading' => 'Fewer open balances, a happier front desk.',
    'accent_word' => 'happier',
    'body' => 'Tell us about your practice, your front desk and your software. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a practice quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
  ]],
];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No healthcare page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  if ($node->hasField('field_meta_description')) {
    $node->set('field_meta_description', 'Card payments for Austin healthcare and dental practices: text-to-pay for balances, payment plans, card on file and a compact front desk terminal.');
  }
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "Healthcare page rebuilt from its short version.\n";
}
else {
  print "Healthcare page already built; left as it is.\n";
}
