<?php

/**
 * @file
 * The contract and cancellation answer on Pricing (supplied 2026-11-23).
 *
 * The contract term and cancellation policy are spelled out in each formal
 * quote and are not published online, so the answer says where to find them
 * and states no term or fee.
 *
 * The answer is only replaced while it still holds the [Confirm]
 * placeholder, so an edited answer is kept. On a fresh install
 * scripts/content/20-pages.php already has the answer and this script
 * changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-contract-terms.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$placeholder = '<p>Your agreement terms are spelled out on your quote. Ask us anything before you sign, and we will answer in plain English. [Confirm current term and cancellation policy.]</p>';
$answer = '<p>The contract term and any cancellation fee are spelled out in your formal quote, before you sign. They are not published online. Ask us anything before you sign, and we will answer in plain English.</p>';

$updated = 0;
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
foreach ($storage->loadByProperties(['type' => 'faq_item', 'field_question' => 'Is there a contract or cancellation fee?']) as $item) {
  if ($item->get('field_answer')->value === $placeholder) {
    $item->set('field_answer', ['value' => $answer, 'format' => $item->get('field_answer')->format])->save();
    $updated++;
  }
}

print "Contract terms answer: $updated updated.\n";
