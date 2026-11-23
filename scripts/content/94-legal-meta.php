<?php

/**
 * @file
 * Search descriptions for the Privacy, Terms and Accessibility pages, which
 * were built without one. Each restates what the page already says.
 *
 * Safe to re-run: a description that is already set is left alone. Run it
 * with
 *   ./scripts/drush php:script scripts/content/94-legal-meta.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$descriptions = [
  '/privacy' => 'How Impeccabyte handles the information you send us. We use your details only to answer your request, and we never sell them.',
  '/terms' => 'The terms for using the Impeccabyte website. Merchant accounts are governed by your Merchant Agreement with Maverick Payments, not by this page.',
  '/accessibility' => 'We build this site to meet WCAG 2.2 level AA. If something does not work for you, tell us and we will help and fix it.',
];

$set = 0;
foreach ($descriptions as $alias => $description) {
  $node = ib_node_by_alias($alias);
  if ($node && $node->hasField('field_meta_description') && $node->get('field_meta_description')->isEmpty()) {
    $node->set('field_meta_description', $description)->save();
    $set++;
  }
}

print "Search descriptions set on $set legal pages.\n";
