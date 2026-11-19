<?php

/**
 * @file
 * Fills out the Specialty industries hub, 2026-11-19.
 *
 * scripts/content/60-specialty.php builds the hub and the twelve vertical
 * pages from the information architecture plan's addenda table. This
 * script adds to the hub only: the hero gains a neutral photo (an owner and
 * a specialist going over paperwork, never products), a "how
 * pre-qualification works" row and a "Keep reading" row. The vertical pages
 * stay text-only, and the five whose requirements are being confirmed keep
 * their placeholder.
 *
 * Tone as on 60-specialty.php: calm and factual, no promise of approval, no
 * "no reserves", no legal advice.
 *
 * Safe to re-run: only a hub still in its first shape is changed. Run it
 * with
 *   ./scripts/drush php:script scripts/content/85-specialty-hub.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/specialty';
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;

$node = ib_node_by_alias($path);
if (!$node) {
  print "No specialty hub yet; run scripts/content/60-specialty.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles !== ['hero', 'feature_grid', 'callout', 'faq', 'cta_band']) {
  print "Specialty hub already filled out or edited; left as it is.\n";
  return;
}
[$hero, $industries, $expect, $faq, $cta] = $existing;

$hero->set('field_hero_variant', 'image_right');
$hero->set('field_media_label', 'a business owner and a local specialist going over paperwork');
$hero->set('field_checklist', ['Requirements laid out up front', 'Your website reviewed first', 'Support from Austin']);
$hero->save();

$node->set('field_sections', [
  $hero,
  $industries,
  $expect,
  ib_paragraph('steps', [
    'eyebrow' => 'How it works',
    'heading' => 'How pre-qualification works',
    'intro' => 'A few steps before the application, so nothing in it comes as a surprise.',
    'items' => [
      $step('Tell us about your business', 'Your industry, your websites, your volume and any licenses you hold.'),
      $step('We review your website', 'We check it against what underwriting asks for and tell you what to fix first.'),
      $step('You gather the paperwork', 'We list the documents your industry needs, so the application goes in complete.'),
      $step('Underwriting decides', 'Maverick Payments and its sponsor banks review the application and make the decision.'),
    ],
  ]),
  $faq,
  ib_paragraph('related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['merchant-account-checklist', 'customer-disputes-a-charge', 'read-a-processing-statement']))),
  ]),
  $cta,
]);
$node->save();
print "Specialty hub filled out.\n";
