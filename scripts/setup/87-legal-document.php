<?php

/**
 * @file
 * The "Legal document" section: a policy served by GetTerms (2026-11-23).
 *
 * Terms and Privacy are written and kept up to date in GetTerms, and the
 * site shows them through GetTerms' embed. The section stores only which
 * document to show and the GetTerms site ID. The legal-document component
 * renders the embed, a loading skeleton and a fallback if it never loads.
 *
 * Added to landing pages only, which is what the legal pages are.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/87-legal-document.php
 * then export config.
 */

use Drupal\field\Entity\FieldConfig;

require_once __DIR__ . '/_helpers.php';

ib_paragraph_type('legal_document', 'Legal document', 'A policy from GetTerms, such as the terms of service or the privacy policy.');
ib_field('paragraph', 'legal_document', 'field_document', 'list_string', 'Document', [
  'storage' => ['allowed_values' => ['terms-of-service' => 'Terms of service', 'privacy' => 'Privacy policy']],
  'required' => TRUE,
  'widget' => 'options_select',
  'weight' => 0,
  'hide' => TRUE,
]);
ib_field('paragraph', 'legal_document', 'field_getterms_id', 'string', 'GetTerms site ID', [
  'storage' => ['max_length' => 32],
  'required' => TRUE,
  'default' => '7RS3i',
  'description' => 'The data-getterms value in the embed code GetTerms gives you.',
  'weight' => 1,
  'hide' => TRUE,
]);

// Landing pages can use the new section.
$field = FieldConfig::loadByName('node', 'landing_page', 'field_sections');
$settings = $field->getSetting('handler_settings');
if (empty($settings['target_bundles']['legal_document'])) {
  $settings['target_bundles']['legal_document'] = 'legal_document';
  $field->setSetting('handler_settings', $settings)->save();
}

print "Legal document section is in place.\n";
