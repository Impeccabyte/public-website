<?php

/**
 * @file
 * Search descriptions for the Privacy and Terms pages, which
 * were built without one. Each restates what the page already says.
 *
 * Safe to re-run: a description that is already set is left alone. Run it
 * with
 *   ./scripts/drush php:script scripts/content/94-legal-meta.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$descriptions = [
  '/privacy' => "Impeccabyte's privacy policy: what information this website collects and how we use and protect it.",
  '/terms' => 'The terms for using the Impeccabyte website. Merchant accounts are governed by your Merchant Agreement with Maverick Payments, not by this page.',
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
