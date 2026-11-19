<?php

/**
 * @file
 * The 10 resources from the resource library wireframe, one of them featured.
 *
 * Titles and summaries are the wireframe's. Article bodies are placeholders
 * until the copy is written. The customer story is marked as a placeholder
 * because it is not a real, approved story. The Banking topic is gone, so
 * the bank account guide sits under Getting started.
 *
 * Safe to re-run: a resource that already exists at its address is left
 * alone. Run it with
 *   ./scripts/drush php:script scripts/content/50-resources.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$term = fn(string $vid, string $name) => ['target_id' => ib_term_id($vid, $name)];

// Slug, type, topic, title, summary, meta, featured, placeholder.
$resources = [
  ['first-merchant-account', 'Guides', 'Getting started', 'Your first merchant account: a plain-English guide for new founders', 'What a merchant account is, what you need to apply, how long underwriting can take and the questions to ask before you sign anything.', '12 min read', TRUE, FALSE],
  ['read-a-processing-statement', 'Guides', 'Pricing and fees', 'How to read a processing statement in 10 minutes', 'Find your effective rate, spot junk fees and know which lines to question.', '8 min read', FALSE, FALSE],
  ['merchant-account-checklist', 'Checklists', 'Getting started', 'What you need to apply for a merchant account', 'EIN or SSN, bank details, ID and a few answers about your business.', 'PDF checklist', FALSE, FALSE],
  ['customer-disputes-a-charge', 'Guides', 'Chargebacks', 'What to do when a customer disputes a charge', 'Deadlines, the evidence that wins and how to stop the next one.', '7 min read', FALSE, FALSE],
  ['clover-flex-first-tableside-payment', 'Videos', 'Hardware', 'Clover Flex: taking your first tableside payment', 'A two-minute walkthrough for new servers.', '2 min video', FALSE, FALSE],
  ['boutique-switching-to-dual-pricing', 'Customer stories', 'Pricing and fees', 'A South Congress boutique on switching to dual pricing', 'How they told regulars, and what happened to their fees.', '4 min read', FALSE, TRUE],
  ['funding-times-explained', 'Guides', 'Funding', 'When will my money land? Funding times explained', 'Batches, cutoffs and why weekends wait. Cutoffs vary by processor.', '5 min read', FALSE, FALSE],
  ['interchange-assessments-markup', 'Glossary', 'Pricing and fees', 'Interchange, assessments and markup, defined', 'The three parts of every card fee in one page.', 'Glossary', FALSE, FALSE],
  ['payment-links-vs-online-store', 'Guides', 'Online payments', 'Payment links vs a full online store', 'Which one you need, and when to upgrade.', '6 min read', FALSE, FALSE],
  ['business-bank-account', 'Guides', 'Getting started', 'Why your business needs its own bank account', 'Cleaner books, simpler applications and fewer tax-time headaches.', '5 min read', FALSE, FALSE],
];

$nodes = [];
// Oldest first, so the newest-first library lists them in wireframe order.
$created = \Drupal::time()->getRequestTime();
foreach ($resources as $index => [$slug, $type, $topic, $title, $summary, $meta, $featured, $placeholder]) {
  $nodes[$slug] = ib_page('resource', $title, '/resources/' . $slug, [], [
    'field_resource_type' => $term('resource_type', $type),
    'field_resource_topic' => $term('resource_topic', $topic),
    'field_summary' => $summary,
    'field_meta' => $meta,
    'field_body' => ib_html($placeholder ? '<p>[Customer story to be supplied. Publish only a real story the customer has approved.]</p>' : '<p>[Article copy to be supplied.]</p>'),
    'field_featured' => (int) $featured,
    'field_placeholder' => (int) $placeholder,
    'created' => $created - $index * 60,
  ]);
}

// "Keep reading" rows on pages built before the resources existed. On a new
// site the page scripts run first and leave these rows empty.
$keep_reading = [
  '/solutions/dual-pricing' => ['boutique-switching-to-dual-pricing', 'read-a-processing-statement', 'interchange-assessments-markup'],
  '/solutions/in-person' => ['merchant-account-checklist', 'clover-flex-first-tableside-payment', 'read-a-processing-statement'],
  '/solutions/online' => ['payment-links-vs-online-store', 'customer-disputes-a-charge', 'interchange-assessments-markup'],
  '/solutions/on-the-go' => ['clover-flex-first-tableside-payment', 'merchant-account-checklist', 'funding-times-explained'],
];
foreach ($keep_reading as $alias => $slugs) {
  if (!($page = ib_node_by_alias($alias))) {
    continue;
  }
  foreach ($page->get('field_sections')->referencedEntities() as $section) {
    if ($section->bundle() === 'related_resources' && $section->get('field_resources')->isEmpty()) {
      $section->set('field_resources', array_map(fn(string $slug) => ['target_id' => $nodes[$slug]->id()], $slugs));
      $section->save();
      $page->save();
    }
  }
}

// The mega menu's featured card points at the founder guide.
$card = \Drupal::service('entity.repository')->loadEntityByUuid('block_content', 'b1f0a8e2-6a53-4c0e-9d7a-5e1c3a7f2d41');
if ($card && $card->get('field_link')->uri === 'internal:/resources') {
  $card->set('field_link', ['uri' => 'internal:/resources/first-merchant-account', 'title' => 'Read the guide'])->save();
}

print count($nodes) . " resources are in place.\n";
