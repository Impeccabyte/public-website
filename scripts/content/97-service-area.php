<?php

/**
 * @file
 * The service area answer on About us (supplied 2026-11-23).
 *
 * Impeccabyte takes on clients outside Central Texas. For them, on-site
 * support is optional and has a fee, listed on the quote like every other
 * fee. No amount is stated on the site.
 *
 * The "Do you only work with Austin businesses?" answer is only replaced
 * while it still holds the [Confirm] placeholder, so an edited answer is
 * kept. On a fresh install scripts/content/89-about.php already has the
 * answer and this script changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-service-area.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$placeholder = '<p>[Confirm: whether we take on businesses outside Austin and Central Texas.]</p>';
$answer = "<p>No. We're based in Austin and work with businesses across Central Texas, and we take on clients outside Central Texas too. Outside Central Texas, on-site support is optional and comes with a fee, which we list on your quote.</p>";

$updated = 0;
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
foreach ($storage->loadByProperties(['type' => 'faq_item', 'field_question' => 'Do you only work with Austin businesses?']) as $item) {
  if ($item->get('field_answer')->value === $placeholder) {
    $item->set('field_answer', ['value' => $answer, 'format' => $item->get('field_answer')->format])->save();
    $updated++;
  }
}

print "Service area answer: $updated updated.\n";
