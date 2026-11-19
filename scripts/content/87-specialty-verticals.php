<?php

/**
 * @file
 * Fills out the twelve specialty vertical pages, 2026-11-19.
 *
 * scripts/content/60-specialty.php builds each page from the addenda table.
 * This script keeps every requirement, fee line and callout exactly as they
 * are, and adds:
 * - a photo hero with a short checklist (the photo is attached by
 *   scripts/content/88-specialty-photos.php; neutral and PG: people, counters
 *   and paperwork, never products in focus, weapons or anything suggestive),
 * - "Keep your account in good standing", general good-practice habits after
 *   approval, with no new underwriting claims,
 * - a "Keep reading" row.
 *
 * The five verticals whose addenda are not reviewed keep their placeholder.
 *
 * Safe to re-run: only a page still in its first shape (a centered hero and
 * no image-and-text section) is changed. Run it with
 *   ./scripts/drush php:script scripts/content/87-specialty-verticals.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;

// Slug => photo description (also the hero's media label until a photo is attached).
$verticals = [
  'hemp-and-cbd' => 'a wellness shop owner talking with a customer at the counter',
  'smoke-and-vape' => 'a specialty shop owner ringing up a purchase at the counter',
  'kratom' => 'a botanical tea shop owner weighing an order behind the counter',
  'firearms' => 'an outdoor goods store owner handing a customer a bag at the counter',
  'adult-content' => 'an independent creator reviewing her business accounts in a home studio',
  'dating' => 'two app founders looking at a phone together in their office',
  'gaming' => 'two colleagues talking at a desk in a small games company office',
  'collections' => 'an account representative on a call at a tidy desk',
  'credit-repair' => 'an advisor and a client reviewing a document together',
  'lending' => 'a loan officer handing a client a folder across the desk',
  'nutraceuticals-and-subscriptions' => 'a wellness brand team packing orders in a small warehouse',
  'online-pharmacy-and-telehealth' => 'a clinician on a video consultation in a calm home office',
];
$pending = ['firearms', 'dating', 'collections', 'credit-repair', 'lending'];

$changed = 0;
foreach ($verticals as $slug => $photo) {
  $path = '/industries/specialty/' . $slug;
  $node = ib_node_by_alias($path);
  if (!$node) {
    continue;
  }
  $existing = $node->get('field_sections')->referencedEntities();
  $bundles = array_map(fn($section) => $section->bundle(), $existing);
  if ($bundles[0] !== 'hero' || $existing[0]->get('field_hero_variant')->value !== 'centered' || in_array('media_split', $bundles, TRUE)) {
    continue;
  }

  $hero = $existing[0];
  $hero->set('field_hero_variant', 'image_right');
  $hero->set('field_media_label', $photo);
  $hero->set('field_checklist', [
    in_array($slug, $pending, TRUE) ? 'Requirements being confirmed' : 'Requirements laid out up front',
    'Your website reviewed first',
    'Support from Austin',
  ]);
  $hero->save();

  $good_standing = ib_paragraph('media_split', [
    'eyebrow' => 'After approval',
    'heading' => 'Keep your account in good standing',
    'body' => 'Specialty accounts are watched closely once they are open. A few habits keep yours healthy and make any review simpler.',
    'checklist' => [
      'Use a billing name customers will recognize on their statement',
      'Post a clear refund and cancellation policy where customers can find it',
      'Answer disputes quickly, using the alerts in your dashboard',
      'Tell us before you add new products or websites',
    ],
    'link' => ib_link('/solutions/chargeback-protection', 'See chargeback protection'),
    'media_label' => 'an owner reviewing her merchant account dashboard',
  ]);
  $reading = ib_paragraph('related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $resource) => $node_ref('/resources/' . $resource), ['merchant-account-checklist', 'customer-disputes-a-charge', 'read-a-processing-statement']))),
  ]);

  // Insert the new sections before the pre-qualification form and before
  // the closing band; everything else keeps its order.
  $sections = [];
  foreach ($existing as $section) {
    if ($section->bundle() === 'webform_embed') {
      $sections[] = $good_standing;
    }
    if ($section->bundle() === 'cta_band') {
      $sections[] = $reading;
    }
    $sections[] = $section;
  }
  $node->set('field_sections', $sections);
  $node->save();
  $changed++;
}

print "Specialty verticals filled out: $changed.\n";
