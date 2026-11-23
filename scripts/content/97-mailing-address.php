<?php

/**
 * @file
 * The company mailing address on About us (supplied 2026-11-23).
 *
 * Impeccabyte's mailing address takes mail only: visitors are not received
 * there, so no copy invites anyone to stop by. Local specialists work in
 * Austin. The footer shows the same address from the site settings
 * (config/sync/impeccabyte_site.settings.yml).
 *
 * On About us, the "Find us" section and the "How do I reach you?" answer
 * lose the office placeholder and the "stop by" wording. A value is only
 * replaced while it still holds the old wording, so anything edited in the
 * admin is kept. On a fresh install scripts/content/89-about.php already
 * has the new wording and this script changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-mailing-address.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$address = '17350 State Hwy 249, Ste 220 #37005, Houston, TX 77064';
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
$updated = 0;

foreach ($storage->loadByProperties(['type' => 'media_split', 'field_heading' => 'A real person, close by']) as $section) {
  $changed = FALSE;
  if ($section->get('field_eyebrow')->value === 'Find us') {
    $section->set('field_eyebrow', 'Reach us');
    $changed = TRUE;
  }
  if ($section->get('field_body')->value === 'Call, write or stop by. When something goes wrong at the register, you reach someone who knows your setup.') {
    $section->set('field_body', 'Call or email, and a local specialist in Austin is nearby and ready to help. When something goes wrong at the register, you reach someone who knows your setup.');
    $changed = TRUE;
  }
  $checklist = array_column($section->get('field_checklist')->getValue(), 'value');
  if (($i = array_search('Office: [Street address], Austin, TX [ZIP]', $checklist, TRUE)) !== FALSE) {
    $checklist[$i] = 'Mailing address, mail only: ' . $address;
    $section->set('field_checklist', $checklist);
    $changed = TRUE;
  }
  if ($changed) {
    $section->save();
    $updated++;
  }
}

$old_answer = '<p>Call us at [Phone number], use the quote form on any page, or stop by our office at [Street address]. A local specialist replies within one business day.</p>';
$new_answer = "<p>Call us at [Phone number] or use the quote form on any page. A local specialist in Austin replies within one business day. Our mailing address is $address. It takes mail only, so we can't meet you there.</p>";
foreach ($storage->loadByProperties(['type' => 'faq_item', 'field_question' => 'How do I reach you?']) as $item) {
  if ($item->get('field_answer')->value === $old_answer) {
    $item->set('field_answer', ['value' => $new_answer, 'format' => $item->get('field_answer')->format])->save();
    $updated++;
  }
}

print "Mailing address on About us: $updated value(s) updated.\n";
