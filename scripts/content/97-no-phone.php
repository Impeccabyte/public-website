<?php

/**
 * @file
 * No company phone number, and howdy@impeccabyte.com as the contact email
 * (2026-11-23).
 *
 * The phone setting is empty in config/sync, and the header, menus, quote
 * page and footer show the contact email wherever they showed the phone.
 * This script takes the phone out of page copy: the About us contact
 * section and FAQ, and the lines on other pages that asked people to call.
 *
 * A value is only replaced while it still holds the old wording, so anything
 * edited in the admin is kept. On a fresh install the source scripts already
 * have the new wording and this script changes nothing. Runs after
 * scripts/content/97-mailing-address.php.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-no-phone.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$email = 'howdy@impeccabyte.com';

// Old wording => new wording, matched inside any text field of a paragraph.
$swaps = [
  'Call or email, and a local specialist in Austin is nearby and ready to help.' => 'Email us, and a local specialist in Austin is nearby and ready to help.',
  'Call us at [Phone number] or use the quote form on any page.' => "Email us at $email or use the quote form on any page.",
  'For a tricky dispute, call us and we will walk through it with you.' => 'For a tricky dispute, email us and we will walk through it with you.',
  'We walk you through it by phone or in person.' => 'We walk you through it in person or by email.',
  // The interim wording from earlier on 2026-11-23.
  'We walk you through it in person.' => 'We walk you through it in person or by email.',
  'Three short steps online, or a call. We ask' => 'Three short steps online. We ask',
  'email hello@impeccabyte.com or call (512) [XXX-XXXX] and we will help' => "email $email and we will help",
  'One local team that answers the phone when your volunteers have a question.' => 'One local team that answers when your volunteers have a question.',
];

$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
$text_types = ['string', 'string_long', 'text', 'text_long', 'text_with_summary'];
$updated = 0;

foreach ($storage->loadMultiple() as $paragraph) {
  $changed = FALSE;
  foreach ($paragraph->getFields() as $name => $field) {
    if (!str_starts_with($name, 'field_') || !in_array($field->getFieldDefinition()->getType(), $text_types, TRUE)) {
      continue;
    }
    $values = $field->getValue();
    foreach ($values as $delta => $item) {
      $value = $item['value'] ?? '';
      $new = $value;
      foreach ($swaps as $old => $replacement) {
        $new = str_replace($old, $replacement, $new);
      }
      // The About us contact checklist: the phone line becomes the email,
      // listed first.
      if ($new === 'Phone: [Phone number]') {
        $new = "Email: $email";
      }
      if ($new !== $value) {
        $values[$delta]['value'] = $new;
        $changed = TRUE;
      }
    }
    if ($changed) {
      if ($name === 'field_checklist') {
        usort($values, fn($a, $b) => str_starts_with($b['value'], 'Email:') <=> str_starts_with($a['value'], 'Email:'));
      }
      $paragraph->set($name, $values);
    }
  }
  if ($changed) {
    $paragraph->save();
    $updated++;
  }
}

print "No phone number: $updated paragraph(s) updated.\n";
