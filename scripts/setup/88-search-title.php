<?php

/**
 * @file
 * A "Search title" for pages (2026-11-23, from docs/seo-keywords.md).
 *
 * The browser-tab title search engines show. Pages keep their creative
 * headline (H1); the search title carries the words people search for.
 * When it is empty the title stays "[page title] | Impeccabyte".
 * impeccabyte_site_metatags_alter() applies it.
 *
 * Added to landing pages, solutions and industry pages. Device pages use a
 * pattern instead: "[device]: price, specs and setup | Impeccabyte"
 * (metatag defaults for devices). The hardware catalog, a view, gets
 * "Credit card machines and POS hardware | Impeccabyte".
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/88-search-title.php
 * then export config.
 */

require_once __DIR__ . '/_helpers.php';

foreach (['landing_page', 'solution', 'industry_page'] as $bundle) {
  ib_field('node', $bundle, 'field_search_title', 'string', 'Search title', [
    'storage' => ['max_length' => 90],
    'description' => 'The title search engines show, with the words people search for, ending in "| Impeccabyte". Aim for 60 characters or fewer. Leave empty to use the page title.',
    'weight' => 90,
    'hide' => TRUE,
  ]);
}

// Device pages: a pattern, so every device gets one without editing.
$defaults = \Drupal::entityTypeManager()->getStorage('metatag_defaults')->load('node__device');
if ($defaults) {
  $tags = $defaults->get('tags');
  if (empty($tags['title'])) {
    $tags['title'] = '[node:title]: price, specs and setup | [site:name]';
    $defaults->set('tags', $tags)->save();
  }
}

// The hardware catalog is a view, so its title is set on the view display
// through Metatag Views (like its description in 85-meta-descriptions.php).
$view = \Drupal\views\Entity\View::load('hardware_catalog');
if ($view) {
  $display = &$view->getDisplay('page_1');
  $tags = $display['display_options']['display_extenders']['metatag_display_extender']['metatags'] ?? [];
  if (empty($tags['title'])) {
    $tags['title'] = 'Credit card machines and POS hardware | [site:name]';
    $display['display_options']['display_extenders']['metatag_display_extender']['metatags'] = $tags;
    $view->save();
  }
  unset($display);
}

print "Search titles are in place.\n";
