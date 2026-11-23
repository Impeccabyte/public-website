<?php

/**
 * @file
 * Icons on the "Who we serve" industry cards on About us (2026-11-23).
 *
 * Each card gets the same icon as its link in the Industries menu, read from
 * the menu itself so the two stay in step. A card that already has an icon
 * is left alone, so anything chosen in the admin is kept. On a fresh install
 * scripts/content/89-about.php already sets these icons and this script
 * changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-about-industry-icons.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Industry page address => icon, from the Industries menu.
$icons = [];
$links = \Drupal::entityTypeManager()->getStorage('menu_link_content')->loadByProperties(['menu_name' => 'main']);
foreach ($links as $link) {
  $uri = $link->get('link')->uri;
  if (str_starts_with($uri, 'internal:/industries/') && $link->hasField('field_icon') && !$link->get('field_icon')->isEmpty()) {
    $icons[substr($uri, strlen('internal:'))] = $link->get('field_icon')->value;
  }
}

$updated = 0;
$page = ib_node_by_alias('/about');
if ($page && $icons) {
  foreach ($page->get('field_sections')->referencedEntities() as $section) {
    if ($section->bundle() !== 'feature_grid' || $section->get('field_heading')->value !== "Built for Austin's small businesses") {
      continue;
    }
    foreach ($section->get('field_items')->referencedEntities() as $card) {
      $uri = $card->get('field_link')->uri ?? '';
      $path = str_starts_with($uri, 'internal:') ? substr($uri, strlen('internal:')) : $uri;
      if (isset($icons[$path]) && $card->get('field_icon')->isEmpty()) {
        $card->set('field_icon', $icons[$path])->save();
        $updated++;
      }
    }
  }
}
if ($updated) {
  \Drupal::service('cache_tags.invalidator')->invalidateTags(['node:' . $page->id()]);
}

print "About us industry icons: $updated card(s) updated.\n";
