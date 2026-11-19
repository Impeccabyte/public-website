<?php

/**
 * @file
 * Phase 1, foundation: site settings, vocabularies and their first terms,
 * the content types' shells and their URL patterns.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/10-foundation.php
 * then export config.
 */

use Drupal\taxonomy\Entity\Vocabulary;

require_once __DIR__ . '/_helpers.php';

$installer = \Drupal::service('module_installer');
$installer->install(['impeccabyte_site']);

// Site basics. Austin is on Central time.
$config = \Drupal::configFactory();
$config->getEditable('system.site')->set('name', 'Impeccabyte')->set('slogan', 'Impeccably convenient.')->save();
$config->getEditable('system.date')->set('timezone.default', 'America/Chicago')->set('country.default', 'US')->set('first_day', 0)->save();
$config->getEditable('user.settings')->set('register', 'admin_only')->save();

// The standard profile's free-tagging vocabulary is not used: every
// vocabulary here is curated.
if (($tags = Vocabulary::load('tags')) && !\Drupal::entityQuery('taxonomy_term')->accessCheck(FALSE)->condition('vid', 'tags')->count()->execute()) {
  $tags->delete();
}

// Vocabularies.
ib_vocabulary('device_type', 'Device type', 'What kind of hardware a device is.');
ib_vocabulary('sales_channel', 'Sales channel', 'Where a device takes payments.');
ib_vocabulary('industry', 'Industry', 'The kinds of business the site speaks to. Two levels at most.');
ib_vocabulary('manufacturer', 'Manufacturer', 'Who makes a device.');
ib_vocabulary('solution_category', 'Solution category', 'Groups solutions in the menu.');
ib_vocabulary('resource_type', 'Resource type', 'The format of a resource.');
ib_vocabulary('resource_topic', 'Resource topic', 'What a resource is about.');

// Industry terms carry how Maverick underwrites them.
ib_field('taxonomy_term', 'industry', 'field_risk_tier', 'list_string', 'Risk tier', [
  'storage' => ['allowed_values' => ['standard' => 'Standard', 'specialty' => 'Specialty']],
  'required' => TRUE,
  'default' => 'standard',
  'widget' => 'options_select',
  'description' => 'Specialty industries go through extra underwriting and get their own pages under the specialty hub.',
  'weight' => 1,
]);
ib_field('taxonomy_term', 'industry', 'field_maverick_addendum', 'string', 'Maverick addendum', [
  'description' => 'The name of the Maverick addendum a business in this industry signs, if any.',
  'weight' => 2,
]);

// First terms, in the order they should list.
$terms = [
  'device_type' => ['Countertop terminal', 'Handheld', 'Mobile reader', 'POS system', 'Self-order kiosk'],
  'sales_channel' => ['In store', 'Tableside', 'On the go', 'Online and in person'],
  'industry' => ['Restaurants and bars', 'Food trucks', 'Retail shops', 'Salons and spas', 'Home services', 'Healthcare and dental', 'Auto repair', 'Professional services', 'Nonprofits', 'Pop-ups and markets'],
  // Clover leads: it is the hero hardware.
  'manufacturer' => ['Clover', 'Dejavoo', 'PAX', 'Valor', 'SwipeSimple'],
  'solution_category' => ['Accept payments', 'Lower your costs', 'Run your business'],
  'resource_type' => ['Guides', 'Checklists', 'Videos', 'Customer stories', 'Glossary'],
  // Merchant services only at launch, so there is no Banking topic.
  'resource_topic' => ['Getting started', 'Pricing and fees', 'Hardware', 'Online payments', 'Chargebacks', 'Funding'],
];
foreach ($terms as $vid => $names) {
  foreach ($names as $weight => $name) {
    ib_term($vid, $name, $weight);
  }
}

// Content type shells. Their fields are added by the later setup scripts.
ib_node_type('landing_page', 'Landing page', 'A page built from sections. Used for Home and Pricing.');
ib_node_type('solution', 'Solution', 'One thing Impeccabyte helps a business do, such as dual pricing.');
ib_node_type('industry_page', 'Industry page', 'A landing page for one industry.');
ib_node_type('device', 'Device', 'One piece of payment hardware in the catalog.');
ib_node_type('resource', 'Resource', 'A guide, checklist, video, customer story or glossary entry.');

// URL patterns.
$config->getEditable('pathauto.settings')->set('punctuation.hyphen', 1)->save();
ib_pathauto('landing_page', 'Landing pages', '/[node:title]', 'node', 'landing_page');
ib_pathauto('solution', 'Solutions', '/solutions/[node:title]', 'node', 'solution');
ib_pathauto('industry_page', 'Industry pages', '/industries/[node:title]', 'node', 'industry_page');
ib_pathauto('device', 'Devices', '/hardware/[node:title]', 'node', 'device');
ib_pathauto('resource', 'Resources', '/resources/[node:title]', 'node', 'resource');

// Anyone may view published content and media.
user_role_grant_permissions('anonymous', ['access content', 'view media']);
user_role_grant_permissions('authenticated', ['access content', 'view media']);

print "Foundation is in place.\n";
