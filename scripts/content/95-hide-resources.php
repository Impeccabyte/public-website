<?php

/**
 * @file
 * Takes the Resources section offline for now (2026-11-21).
 *
 * Nothing is deleted, so it can come back:
 * - The 10 resource pages are unpublished. The "related resources" section on
 *   solution and industry pages prints nothing once its cards are not
 *   viewable, so those sections disappear on their own.
 * - The 8 menu links to /resources (main menu and footer) are disabled.
 * - Buttons and links that pointed at a resource are cleared. Their old
 *   targets are listed in $links below to put back.
 *
 * The /resources library page, the "Founder's guide" menu card and the
 * sitemap entry are switched off in scripts/setup/86-hide-resources.php.
 *
 * To bring Resources back: publish the resource nodes, re-enable the menu
 * links, restore the links below, and reverse the setup script.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/95-hide-resources.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$storage = \Drupal::entityTypeManager();
$done = [];

// Resource pages.
foreach ($storage->getStorage('node')->loadByProperties(['type' => 'resource', 'status' => 1]) as $node) {
  $node->setUnpublished()->save();
  $done[] = 'unpublished ' . $node->toUrl()->toString();
}

// Menu links to the library and its filtered views.
foreach ($storage->getStorage('menu_link_content')->loadMultiple() as $link) {
  if ($link->isEnabled() && preg_match('#^internal:/resources([/?]|$)#', $link->get('link')->uri)) {
    $link->set('enabled', FALSE)->save();
    $done[] = 'disabled menu link "' . $link->label() . '" (' . $link->getMenuName() . ')';
  }
}

// Paragraph links that pointed at Resources. Previous targets:
// - media_split field_link: /resources/read-a-processing-statement (2),
//   /resources/customer-disputes-a-charge
// - hero field_link_secondary: /resources/funding-times-explained,
//   /resources/customer-disputes-a-charge
// - cta_band field_link_secondary: /resources/funding-times-explained,
//   /resources/customer-disputes-a-charge, /resources
// - case_study field_link: /resources
$links = ['field_link', 'field_link_secondary'];
$paragraphs = $storage->getStorage('paragraph');
foreach ($links as $field) {
  $ids = $paragraphs->getQuery()->accessCheck(FALSE)->condition($field . '.uri', 'internal:/resources', 'STARTS_WITH')->execute();
  foreach ($paragraphs->loadMultiple($ids) as $paragraph) {
    $old = $paragraph->get($field)->uri;
    $paragraph->set($field, NULL)->save();
    $done[] = 'cleared ' . $paragraph->bundle() . ' ' . $field . ' (was ' . $old . ')';
  }
}

if ($done) {
  \Drupal::service('cache_tags.invalidator')->invalidateTags(['rendered', 'node_list', 'config:system.menu.main', 'config:system.menu.footer-company']);
}
print $done ? "Resources hidden:\n- " . implode("\n- ", $done) . "\n" : "Resources already hidden.\n";
