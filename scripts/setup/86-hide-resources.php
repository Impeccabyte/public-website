<?php

/**
 * @file
 * Switches off the Resources section's structure for now (2026-11-21): the
 * /resources library page, the "Founder's guide" card in the menu (it links
 * to a resource), and the /resources sitemap entry. The content side is in
 * scripts/content/95-hide-resources.php.
 *
 * To bring Resources back, set the view display and the block back to
 * enabled and re-add the sitemap link (80-polish.php adds it when missing).
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/86-hide-resources.php
 * then export config.
 */

use Drupal\block\Entity\Block;
use Drupal\views\Entity\View;

require_once __DIR__ . '/_helpers.php';

if ($view = View::load('resource_library')) {
  $display = &$view->getDisplay('page_1');
  if (($display['display_options']['enabled'] ?? TRUE) !== FALSE) {
    $display['display_options']['enabled'] = FALSE;
    $view->save();
  }
  unset($display);
}

if (($block = Block::load('impeccabyte_featured_card')) && $block->status()) {
  $block->disable()->save();
}

$custom_links = \Drupal::service('simple_sitemap.custom_link_manager');
// Links come back grouped by sitemap variant; this site has only "default".
$all = $custom_links->setVariants(['default'])->get()['default'] ?? [];
$links = array_filter($all, fn(array $link) => $link['path'] !== '/resources');
if (count($links) !== count($all)) {
  $custom_links->remove();
  foreach ($links as $link) {
    $custom_links->add($link['path'], array_diff_key($link, ['path' => TRUE]));
  }
}

\Drupal::service('router.builder')->rebuild();
print "Resources library page, menu card and sitemap entry switched off.\n";
