<?php

/**
 * @file
 * Last content: the legal pages, the error pages and search descriptions.
 *
 * Terms and Privacy come from GetTerms. Search descriptions
 * are taken from each page's own hero lead where none has been written.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/70-polish.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Terms and Privacy are written in GetTerms and shown through its embed
// (legal_document, 2026-11-23). There is no Accessibility page.
$legal = [
  ['Privacy', '/privacy', 'How we handle your information.', 'information', 'privacy'],
  ['Terms', '/terms', 'The terms for using this website.', 'terms', 'terms-of-service'],
];
foreach ($legal as [$title, $path, $headline, $accent, $document]) {
  ib_page('landing_page', $title, $path, [
    ['hero', ['hero_variant' => 'centered', 'eyebrow' => $title, 'heading' => $headline, 'accent_word' => $accent]],
    ['legal_document', ['document' => $document, 'getterms_id' => '7RS3i']],
  ]);
}

// Error pages.
$not_found = ib_page('landing_page', 'Page not found', '/page-not-found', [
  ['hero', ['hero_variant' => 'centered', 'eyebrow' => 'Page not found', 'heading' => "We can't find that page.", 'accent_word' => 'find', 'intro' => 'The link may be old or mistyped. These will get you back on track.', 'link' => ib_link('/', 'Go to the home page'), 'link_secondary' => ib_link('/hardware', 'Browse hardware')]],
  ['cta_band', ['heading' => 'Looking for something specific?', 'accent_word' => 'specific', 'body' => 'Tell us what you need and a local specialist will reply within one business day.', 'link' => ib_link('/get-a-quote', 'Get a quote'), 'link_secondary' => ib_link('/resources', 'Read our guides')]],
]);
$denied = ib_page('landing_page', 'Log in needed', '/log-in-needed', [
  ['hero', ['hero_variant' => 'centered', 'eyebrow' => 'Log in needed', 'heading' => 'That page is for our team.', 'accent_word' => 'team', 'intro' => 'If you are a merchant looking for your account, the merchant app is the place to log in.', 'link' => ib_link('https://app.impeccabyte.com', 'Log in to the merchant app'), 'link_secondary' => ib_link('/', 'Go to the home page')]],
]);
// By address, not node ID: IDs differ between installs, and the exported
// config must match every site it is imported into.
$site = \Drupal::configFactory()->getEditable('system.site');
if ($site->get('page.404') !== '/page-not-found' || $site->get('page.403') !== '/log-in-needed') {
  $site->set('page.404', '/page-not-found')->set('page.403', '/log-in-needed')->save();
}
// Error pages stay out of the XML sitemap.
$sitemap_entities = \Drupal::service('simple_sitemap.entity_manager');
foreach ([$not_found, $denied] as $node) {
  $sitemap_entities->setEntityInstanceSettings('node', $node->id(), ['index' => FALSE]);
}

// Search descriptions from each page's own hero lead, where none is written.
$storage = \Drupal::entityTypeManager()->getStorage('node');
$ids = $storage->getQuery()->accessCheck(FALSE)->condition('type', ['landing_page', 'solution', 'industry_page'], 'IN')->notExists('field_meta_description')->execute();
$filled = 0;
foreach ($storage->loadMultiple($ids) as $node) {
  foreach ($node->get('field_sections')->referencedEntities() as $section) {
    if ($section->bundle() === 'hero' && ($lead = trim((string) $section->get('field_intro')->value))) {
      if (mb_strlen($lead) > 158) {
        $lead = preg_replace('/\s+\S*$/u', '', mb_substr($lead, 0, 156)) . '…';
      }
      $node->set('field_meta_description', $lead)->save();
      $filled++;
      break;
    }
  }
}

\Drupal::service('simple_sitemap.generator')->generate('backend');

print "Legal and error pages are in place. $filled search descriptions filled.\n";
