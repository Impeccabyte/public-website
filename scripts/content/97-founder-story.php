<?php

/**
 * @file
 * The founder story on About us, in the founder's words (supplied
 * 2026-11-21). Impeccabyte was founded in 2025 by Ryan Bealey.
 *
 * Safe to re-run: the story only replaces the original placeholder, so an
 * edited story is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/97-founder-story.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$placeholder = "[Founder story: why Impeccabyte started in [Year founded] and what [Founder name] did before, in the founder's own words. Two or three short paragraphs, signed by [Founder name], founder.]";
$story = "When my mom opened Luxury On A Leash, her puppy boutique, in 2012, I became the family's website guy. The online store, the hosting and the payment integration all landed with me, and that's where my passion for technology began.\n\nI also saw the part of running a small business nobody warns you about. Every month my mom sat down with a processing statement that was hard to make sense of, and every month the fees caught her off guard. For a small business those fees add up quickly, and no one had explained them when she started. I didn't love watching that.\n\nI was raised on a simple idea: do the next right thing. It still guides me, at home and at work. In 2025 I started Impeccabyte to do the next right thing for small businesses, setting them up to run smoothly from day one, with pricing they understand and fees that never come as a surprise.\n\nRyan Bealey, Founder";

$updated = 0;
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
foreach ($storage->loadByProperties(['type' => 'media_split', 'field_body' => $placeholder]) as $section) {
  $section->set('field_body', $story)->save();
  $updated++;
}
// The founder's name, supplied after the story: fill it in wherever the
// placeholder is still there.
foreach ($storage->loadByProperties(['type' => 'media_split']) as $section) {
  $body = (string) $section->get('field_body')->value;
  $label = (string) $section->get('field_media_label')->value;
  $new_body = str_replace("[Founder name], founder", "Ryan Bealey, Founder", $body);
  $new_label = str_replace('[Founder name], photo to be supplied', 'Ryan Bealey, photo to be supplied', $label);
  if ($new_body !== $body || $new_label !== $label) {
    $section->set('field_body', $new_body)->set('field_media_label', $new_label)->save();
    $updated++;
  }
}
if ($updated) {
  \Drupal::service('cache_tags.invalidator')->invalidateTags(['rendered']);
}
print "Founder story: $updated section updated.\n";
