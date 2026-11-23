<?php

/**
 * @file
 * Which phones take tap to pay, on the On the Go FAQ (supplied 2026-11-23).
 *
 * Tap to pay works on most smartphones, iPhone and Android. The answer drops
 * the "[Supported phones and setup steps to be confirmed.]" placeholder and
 * says so. Setup steps are not listed on the site.
 *
 * The answer is only replaced while it still holds the placeholder, so an
 * edited answer is kept. On a fresh install scripts/content/49-on-the-go.php
 * already has the answer and this script changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-tap-to-pay-phones.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$placeholder = '<p>Yes, with tap to pay you can accept contactless cards and phone wallets on a supported phone with no extra hardware. [Supported phones and setup steps to be confirmed.] For chip cards, add a reader that pairs with your phone.</p>';
$answer = '<p>Yes, with tap to pay you can accept contactless cards and phone wallets on most smartphones, iPhone and Android alike, with no extra hardware. For chip cards, add a reader that pairs with your phone.</p>';

$updated = 0;
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
foreach ($storage->loadByProperties(['type' => 'faq_item', 'field_question' => 'Can I take payments on my own phone?']) as $item) {
  if ($item->get('field_answer')->value === $placeholder) {
    $item->set('field_answer', ['value' => $answer, 'format' => $item->get('field_answer')->format])->save();
    $updated++;
  }
}

print "Tap to pay phones answer: $updated updated.\n";
