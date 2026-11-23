<?php

/**
 * @file
 * Search descriptions for the pages that are not nodes: the hardware catalog
 * and resource library (views) and the quote form (a webform).
 *
 * Nodes get theirs from their "Search description" field through the
 * metatag defaults in 80-polish.php. The two views get theirs from Metatag
 * Views on their page display. The webform gets its from a third-party
 * setting that impeccabyte_site_metatags_alter() reads.
 *
 * Safe to re-run: a description that is already set is left alone. Run it
 * with
 *   ./scripts/drush php:script scripts/setup/85-meta-descriptions.php
 * then export config.
 */

use Drupal\views\Entity\View;
use Drupal\webform\Entity\Webform;

require_once __DIR__ . '/_helpers.php';

\Drupal::service('module_installer')->install(['metatag_views']);

$views = [
  'hardware_catalog' => 'Card terminals, handhelds, mobile readers and POS systems from Clover, Dejavoo, PAX, Valor and more. Every device arrives programmed for your account.',
  'resource_library' => 'Plain-English answers about fees, hardware, chargebacks and getting started, written by our team in Austin.',
];
foreach ($views as $id => $description) {
  $view = View::load($id);
  if (!$view) {
    continue;
  }
  $display = &$view->getDisplay('page_1');
  $tags = $display['display_options']['display_extenders']['metatag_display_extender']['metatags'] ?? [];
  if (empty($tags['description'])) {
    $tags['description'] = $description;
    $tags['og_description'] = $description;
    $display['display_options']['display_extenders']['metatag_display_extender']['metatags'] = $tags;
    $view->save();
  }
  unset($display);
}

$webform = Webform::load('get_a_quote');
if ($webform && !$webform->getThirdPartySetting('impeccabyte_site', 'meta_description')) {
  $webform->setThirdPartySetting('impeccabyte_site', 'meta_description', 'Tell us about your business and how you take payments, and a local specialist will put together your quote. A quote is just a quote, and we never sell your info.');
  $webform->save();
}

print "Search descriptions set on the catalog, resource library and quote form.\n";
