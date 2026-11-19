<?php

/**
 * @file
 * Phase 4, devices: the device content type's fields and the paragraph
 * types used on a device page.
 *
 * Prices are plain numbers. Templates format them, and hide them while
 * "Show prices" is off. Nothing here comes from internal pricing sheets.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/40-devices.php
 * then export config.
 */

require_once __DIR__ . '/_helpers.php';

$p = 'paragraph';

ib_paragraph_type('spec_row', 'Spec row', 'One line of a device spec table.');
ib_field($p, 'spec_row', 'field_label', 'string', 'Label', ['required' => TRUE, 'weight' => 0]);
ib_field($p, 'spec_row', 'field_spec_value', 'string_long', 'Value', ['required' => TRUE, 'weight' => 1, 'widget_settings' => ['rows' => 2]]);

ib_paragraph_type('fee_row', 'Monthly fee', 'One monthly software fee on a device.');
ib_field($p, 'fee_row', 'field_label', 'string', 'Fee', ['required' => TRUE, 'weight' => 0]);
ib_field($p, 'fee_row', 'field_amount', 'decimal', 'Amount per month', ['storage' => ['precision' => 10, 'scale' => 2], 'settings' => ['prefix' => '$'], 'weight' => 1]);

ib_paragraph_type('gateway', 'Gateway', 'A payment gateway a device works with.');
ib_field($p, 'gateway', 'field_icon', 'string', 'Icon', ['weight' => 0, 'description' => 'A Lucide icon name, such as credit-card or globe.']);
ib_field($p, 'gateway', 'field_title', 'string', 'Name', ['required' => TRUE, 'weight' => 1]);
ib_field($p, 'gateway', 'field_body', 'string_long', 'Description', ['weight' => 2, 'widget_settings' => ['rows' => 2]]);

$n = 'node';
$b = 'device';
$terms = fn(string $name, string $label, string $vid, int $cardinality, int $weight, bool $required = FALSE) => ib_field($n, $b, $name, 'entity_reference', $label, ['storage' => ['target_type' => 'taxonomy_term'], 'settings' => ib_ref_terms($vid), 'cardinality' => $cardinality, 'required' => $required, 'widget' => $cardinality === 1 ? 'options_select' : 'options_buttons', 'weight' => $weight]);
$price = fn(string $name, string $label, int $weight, string $description) => ib_field($n, $b, $name, 'decimal', $label, ['storage' => ['precision' => 10, 'scale' => 2], 'settings' => ['prefix' => '$', 'min' => 0], 'weight' => $weight, 'description' => $description]);
$flag = fn(string $name, string $label, int $weight, string $description = '', $default = NULL) => ib_field($n, $b, $name, 'boolean', $label, ['weight' => $weight, 'widget' => 'boolean_checkbox', 'widget_settings' => ['display_label' => TRUE], 'description' => $description, 'default' => $default]);

$terms('field_manufacturer', 'Manufacturer', 'manufacturer', 1, 0, TRUE);
$terms('field_device_type', 'Device type', 'device_type', 1, 1, TRUE);
$terms('field_sales_channels', 'Sales channels', 'sales_channel', -1, 2);
$terms('field_industries', 'Industries', 'industry', -1, 3);
ib_field($n, $b, 'field_card_summary', 'string', 'Short description', ['weight' => 4, 'description' => 'One line for device cards.', 'required' => TRUE]);
ib_field($n, $b, 'field_summary', 'string_long', 'Summary', ['weight' => 5, 'description' => 'One or two sentences under the title on the device page.']);
ib_field($n, $b, 'field_gallery', 'entity_reference', 'Gallery', ['storage' => ['target_type' => 'media'], 'settings' => ib_ref_media(['image']), 'cardinality' => -1, 'widget' => 'media_library_widget', 'weight' => 6, 'description' => 'Product photos. Give each one alt text that says which view it shows.']);
$price('field_lease_price', 'Lease price per month', 7, 'Shown as "From $X/mo" once "Show prices" is on.');
$price('field_buy_price', 'Buy price', 8, 'Shown as "Buy from $X" once "Show prices" is on.');
ib_field($n, $b, 'field_lease_terms', 'string_long', 'Lease terms', ['weight' => 9, 'widget_settings' => ['rows' => 2]]);
ib_field($n, $b, 'field_buy_terms', 'string_long', 'Buy terms', ['weight' => 10, 'widget_settings' => ['rows' => 2]]);
$flag('field_in_stock', 'In stock', 11, '', 1);
$flag('field_dual_pricing_ready', 'Dual pricing ready', 12);
$flag('field_featured', 'Featured', 13, 'Featured devices show in the Home hardware row.');
ib_field($n, $b, 'field_sort_weight', 'integer', 'Recommended order', ['weight' => 14, 'default' => 0, 'description' => 'Lower numbers list first within a manufacturer. Clover always lists first in the recommended sort.']);
ib_field($n, $b, 'field_key_features', 'string', 'Key features', ['cardinality' => -1, 'weight' => 15]);
ib_paragraphs_field($n, $b, 'field_specs', 'Specs', ['spec_row'], -1, 16);
ib_paragraphs_field($n, $b, 'field_fees', 'Monthly fees', ['fee_row'], -1, 17);
ib_paragraphs_field($n, $b, 'field_gateways', 'Compatible gateways', ['gateway'], -1, 18);
ib_field($n, $b, 'field_gateway_note', 'string_long', 'Gateway note', ['weight' => 19, 'widget_settings' => ['rows' => 2]]);
ib_field($n, $b, 'field_documents', 'entity_reference', 'Documents', ['storage' => ['target_type' => 'media'], 'settings' => ib_ref_media(['document']), 'cardinality' => -1, 'widget' => 'media_library_widget', 'weight' => 20, 'description' => 'Spec sheets and quick reference guides.']);
ib_field($n, $b, 'field_related_devices', 'entity_reference', 'Related devices', ['storage' => ['target_type' => 'node'], 'settings' => ib_ref_nodes(['device']), 'cardinality' => 4, 'weight' => 21]);

// The paragraphs widget labels on these fields read better in the singular.
$form = \Drupal::service('entity_display.repository')->getFormDisplay('node', 'device');
foreach (['field_specs' => ['Spec', 'Specs'], 'field_fees' => ['Fee', 'Fees'], 'field_gateways' => ['Gateway', 'Gateways']] as $field => [$one, $many]) {
  $component = $form->getComponent($field);
  if ($component && ($component['settings']['title'] ?? '') !== $one) {
    $component['settings']['title'] = $one;
    $component['settings']['title_plural'] = $many;
    $form->setComponent($field, $component);
  }
}
$form->save();

print "Devices are in place.\n";
