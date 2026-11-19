<?php

/**
 * @file
 * Sections added for the In person page: an image on feature cards and the
 * "image and text" section.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/52-in-person.php
 * then export config.
 */

use Drupal\field\Entity\FieldConfig;

require_once __DIR__ . '/_helpers.php';

$p = 'paragraph';
$media = fn(string $bundle, int $weight) => ib_field($p, $bundle, 'field_media', 'entity_reference', 'Image', ['storage' => ['target_type' => 'media'], 'settings' => ib_ref_media(['image']), 'widget' => 'media_library_widget', 'weight' => $weight]);
$label = fn(string $bundle, int $weight) => ib_field($p, $bundle, 'field_media_label', 'string', 'Photo description', ['weight' => $weight, 'description' => 'What the photo should show. Until an image is chosen, a labeled placeholder is drawn from this.']);

// Feature cards can lead with a photo and carry a short line under the body.
$media('feature_card', 5);
$label('feature_card', 6);
ib_field($p, 'feature_card', 'field_note', 'string', 'Small print', ['weight' => 7, 'description' => 'A short line under the body, such as the devices that fit.']);

// Image and text, side by side.
ib_paragraph_type('media_split', 'Image and text', 'A photo beside a heading, a paragraph, a checklist and one link.');
ib_field($p, 'media_split', 'field_eyebrow', 'string', 'Eyebrow', ['weight' => 0]);
ib_field($p, 'media_split', 'field_heading', 'string', 'Heading', ['weight' => 1, 'required' => TRUE]);
ib_field($p, 'media_split', 'field_body', 'string_long', 'Body', ['weight' => 2, 'widget_settings' => ['rows' => 3]]);
ib_field($p, 'media_split', 'field_checklist', 'string', 'Checklist', ['cardinality' => -1, 'weight' => 3]);
ib_field($p, 'media_split', 'field_link', 'link', 'Link', ['weight' => 4, 'settings' => ['link_type' => 17, 'title' => 2]]);
$media('media_split', 5);
$label('media_split', 6);
ib_field($p, 'media_split', 'field_reverse', 'boolean', 'Put the image on the right', ['weight' => 7, 'widget' => 'boolean_checkbox', 'widget_settings' => ['display_label' => TRUE]]);

// Pages built from sections can use the new section.
foreach (['landing_page', 'solution', 'industry_page'] as $type) {
  $field = FieldConfig::loadByName('node', $type, 'field_sections');
  $settings = $field->getSetting('handler_settings');
  if (empty($settings['target_bundles']['media_split'])) {
    $settings['target_bundles']['media_split'] = 'media_split';
    $field->setSetting('handler_settings', $settings)->save();
  }
}

print "In person sections are in place.\n";
