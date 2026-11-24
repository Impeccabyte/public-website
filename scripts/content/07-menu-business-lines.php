<?php

/**
 * @file
 * Moves the main menu to business lines (2026-11-23).
 *
 * Old: Solutions, Hardware, Industries, Pricing, Company.
 * New: Services (business lines inside it), Who We Serve, Pricing, Company.
 *
 * - Solutions becomes Services, with one business line, Payments, under it.
 *   Accept Payments, Lower Your Costs and Run Your Business move under
 *   Payments, and Hardware moves there as a fourth group. A future line, such
 *   as travel, is a second child of Services; the mega menu shows a list of
 *   lines only once there are two.
 * - Industries becomes Who We Serve.
 * - Company drops Merchant Log In (the header's "Log in" covers it), and
 *   Contact, which opens the quote form, is labelled Get a Quote.
 *
 * Links are moved and renamed, not recreated, so their icons, descriptions
 * and any admin edits come along. This runs before 10-chrome.php, which
 * builds the same structure on a fresh install and then finds these links
 * by their new titles and parents. On a site already moved, or a fresh one,
 * it changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/07-menu-business-lines.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');
$changed = 0;

// A link in the main menu by title and parent (NULL for top level).
$find = function (string $title, $parent = NULL) use ($storage) {
  $query = $storage->getQuery()->accessCheck(FALSE)->condition('menu_name', 'main')->condition('title', $title);
  if ($parent) {
    $query->condition('parent', $parent->getPluginId());
  }
  else {
    $query->condition($query->orConditionGroup()->notExists('parent')->condition('parent', ''));
  }
  $ids = $query->execute();
  return $ids ? $storage->load(reset($ids)) : NULL;
};

$solutions = $find('Solutions');
if ($solutions) {
  $solutions->set('title', 'Services')->save();
  $changed++;
  $payments = $find('Payments', $solutions) ?: ib_menu_link('main', 'Payments', 'route:<nolink>', ['parent' => $solutions, 'weight' => 0]);
  foreach (['Accept Payments', 'Lower Your Costs', 'Run Your Business'] as $group) {
    if ($link = $find($group, $solutions)) {
      $link->set('parent', $payments->getPluginId())->save();
      $changed++;
    }
  }
  if ($hardware = $find('Hardware')) {
    $hardware->set('parent', $payments->getPluginId());
    $hardware->set('link', ['uri' => 'route:<nolink>']);
    $hardware->set('weight', 3);
    $hardware->save();
    $changed++;
  }
}

if ($industries = $find('Industries')) {
  $industries->set('title', 'Who We Serve')->save();
  $changed++;
}

if ($company = $find('Company')) {
  if ($login = $find('Merchant Log In', $company)) {
    $login->delete();
    $changed++;
  }
  if (($contact = $find('Contact', $company)) && $contact->get('link')->uri === 'internal:/get-a-quote') {
    $contact->set('title', 'Get a Quote');
    $contact->set('description', 'Tell us about your business');
    $contact->save();
    $changed++;
  }
}

if ($changed) {
  \Drupal::service('cache_tags.invalidator')->invalidateTags(['config:system.menu.main', 'rendered']);
}
print "Menu business lines: $changed link(s) moved or renamed.\n";
