<?php

/**
 * @file
 * Phase 8, polish: meta tags, the XML sitemap, redirects and the favicon.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/80-polish.php
 * then export config.
 */

use Drupal\metatag\Entity\MetatagDefaults;
use Drupal\redirect\Entity\Redirect;

require_once __DIR__ . '/_helpers.php';

\Drupal::service('module_installer')->install(['metatag_open_graph']);

// A plain meta description on the pages that are built from sections.
foreach (['landing_page', 'solution', 'industry_page'] as $type) {
  ib_field('node', $type, 'field_meta_description', 'string_long', 'Search description', [
    'weight' => 20,
    'description' => 'One or two sentences for search results and link previews, about 155 characters.',
    'widget_settings' => ['rows' => 2],
    'hide' => TRUE,
  ]);
}

// Meta tag defaults. Each is set once and then left to editors.
$defaults = [
  'global' => [NULL, [
    'title' => '[current-page:title] | [site:name]',
    'canonical_url' => '[current-page:url]',
    'og_site_name' => '[site:name]',
    'og_title' => '[current-page:title]',
    'og_url' => '[current-page:url]',
    'og_type' => 'website',
    'og_locale' => 'en_US',
  ]],
  'front' => [NULL, [
    'title' => '[site:name] | Austin merchant services',
    'description' => '[node:field_meta_description]',
    'canonical_url' => '[site:url]',
    'og_title' => '[site:name] | Austin merchant services',
    'og_description' => '[node:field_meta_description]',
    'og_url' => '[site:url]',
  ]],
  'node' => [NULL, [
    'title' => '[node:title] | [site:name]',
    'description' => '[node:field_meta_description]',
    'canonical_url' => '[node:url]',
    'og_title' => '[node:title]',
    'og_description' => '[node:field_meta_description]',
    'og_url' => '[node:url]',
  ]],
  'node__device' => ['Content: Device', [
    'description' => '[node:field_card_summary]',
    'og_description' => '[node:field_card_summary]',
    'og_type' => 'product',
  ]],
  'node__resource' => ['Content: Resource', [
    'description' => '[node:field_summary]',
    'og_description' => '[node:field_summary]',
    'og_type' => 'article',
  ]],
];
$marker = \Drupal::keyValue('impeccabyte_setup');
foreach ($defaults as $id => [$label, $tags]) {
  if ($marker->get('metatag.' . $id)) {
    continue;
  }
  $entity = MetatagDefaults::load($id) ?: MetatagDefaults::create(['id' => $id, 'label' => $label]);
  $entity->set('tags', $tags + ($id === 'node' || $id === 'global' || $id === 'front' ? [] : []));
  $entity->save();
  $marker->set('metatag.' . $id, TRUE);
}

// XML sitemap: every content type, plus the two list pages.
$sitemap_entities = \Drupal::service('simple_sitemap.entity_manager');
$sitemap_entities->enableEntityType('node');
$priorities = ['landing_page' => '1.0', 'solution' => '0.8', 'industry_page' => '0.8', 'device' => '0.7', 'resource' => '0.6'];
foreach ($priorities as $bundle => $priority) {
  $current = $sitemap_entities->getBundleSettings('node', $bundle);
  if (empty($current['index'])) {
    $sitemap_entities->setBundleSettings('node', $bundle, ['index' => TRUE, 'priority' => $priority, 'changefreq' => 'weekly', 'include_images' => FALSE]);
  }
}
$custom_links = \Drupal::service('simple_sitemap.custom_link_manager');
$existing = array_column($custom_links->get(), 'path');
foreach (['/hardware' => '0.9', '/resources' => '0.8', '/get-a-quote' => '0.9'] as $path => $priority) {
  if (!in_array($path, $existing, TRUE)) {
    $custom_links->add($path, ['priority' => $priority, 'changefreq' => 'weekly']);
  }
}

// Redirects: one address per page, and a few addresses people guess.
\Drupal::configFactory()->getEditable('redirect.settings')->set('auto_redirect', TRUE)->set('route_normalizer_enabled', TRUE)->set('default_status_code', 301)->save();
$redirects = \Drupal::entityTypeManager()->getStorage('redirect');
foreach (['contact' => 'internal:/get-a-quote', 'quote' => 'internal:/get-a-quote', 'sitemap' => 'internal:/sitemap.xml', 'statement' => 'internal:/pricing'] as $source => $target) {
  if (!$redirects->loadByProperties(['redirect_source__path' => $source])) {
    $redirect = Redirect::create(['status_code' => 301]);
    $redirect->setSource($source);
    $redirect->setRedirect(preg_replace('#^internal:/#', '', $target));
    $redirect->setLanguage('und');
    $redirect->save();
  }
}

// The theme's own favicon.
\Drupal::configFactory()->getEditable('impeccabyte.settings')
  ->set('favicon.use_default', FALSE)
  ->set('favicon.path', 'themes/custom/impeccabyte/favicon.svg')
  ->set('favicon.mimetype', 'image/svg+xml')
  ->save();

print "Polish settings are in place.\n";
