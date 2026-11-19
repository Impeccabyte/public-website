<?php

/**
 * @file
 * Phase 4, catalog: the device search index, the hardware catalog view with
 * its facets and sorts, the device card views for page sections, and the
 * compare module.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/45-catalog.php
 * then export config.
 */

use Drupal\Core\Entity\Entity\EntityViewMode;
use Drupal\field\Entity\FieldConfig;
use Drupal\search_api\Entity\Index;
use Drupal\search_api\Entity\Server;
use Drupal\views\Entity\View;

require_once __DIR__ . '/_helpers.php';

\Drupal::service('module_installer')->install(['facets_exposed_filters', 'impeccabyte_compare']);

// A view mode for the catalog's cards, which carry the compare toggle.
if (!EntityViewMode::load('node.card')) {
  EntityViewMode::create(['id' => 'node.card', 'targetEntityType' => 'node', 'label' => 'Catalog card'])->save();
}
if (!EntityViewMode::load('node.featured')) {
  EntityViewMode::create(['id' => 'node.featured', 'targetEntityType' => 'node', 'label' => 'Featured'])->save();
}
$displays = \Drupal::service('entity_display.repository');
foreach (['device' => ['card', 'teaser'], 'resource' => ['card', 'teaser', 'featured']] as $bundle => $modes) {
  foreach ($modes as $mode) {
    $display = $displays->getViewDisplay('node', $bundle, $mode);
    if ($display->isNew()) {
      $display->setStatus(TRUE)->save();
    }
  }
}

// Product art can be SVG until photography arrives.
$image_field = FieldConfig::loadByName('media', 'image', 'field_media_image');
if ($image_field && !str_contains($image_field->getSetting('file_extensions'), 'svg')) {
  $image_field->setSetting('file_extensions', $image_field->getSetting('file_extensions') . ' svg')->save();
}

// Search server and device index.
if (!Server::load('database')) {
  Server::create([
    'id' => 'database',
    'name' => 'Database',
    'description' => 'Search in the site database.',
    'backend' => 'search_api_db',
    'backend_config' => ['database' => 'default:default', 'min_chars' => 3, 'matching' => 'partial', 'phrase' => 'bigram'],
  ])->save();
}
if (!Index::load('devices')) {
  $field = fn(string $label, string $path, string $type, array $more = []) => ['label' => $label, 'datasource_id' => 'entity:node', 'property_path' => $path, 'type' => $type] + $more;
  Index::create([
    'id' => 'devices',
    'name' => 'Devices',
    'description' => 'The hardware catalog.',
    'server' => 'database',
    'datasource_settings' => ['entity:node' => ['bundles' => ['default' => FALSE, 'selected' => ['device']], 'languages' => ['default' => TRUE, 'selected' => []]]],
    'field_settings' => [
      'title' => $field('Title', 'title', 'text', ['boost' => 5.0]),
      'card_summary' => $field('Short description', 'field_card_summary', 'text'),
      'device_type' => $field('Device type', 'field_device_type', 'integer'),
      'sales_channel' => $field('Sales channel', 'field_sales_channels', 'integer'),
      'industry' => $field('Industry', 'field_industries', 'integer'),
      'manufacturer' => $field('Manufacturer', 'field_manufacturer', 'integer'),
      'lease_price' => $field('Lease price per month', 'field_lease_price', 'decimal'),
      'buy_price' => $field('Buy price', 'field_buy_price', 'decimal'),
      'dual_pricing_ready' => $field('Dual pricing ready', 'field_dual_pricing_ready', 'boolean'),
      'created' => $field('Authored on', 'created', 'date'),
      'status' => $field('Published', 'status', 'boolean'),
      'recommended_rank' => ['label' => 'Recommended rank', 'property_path' => 'impeccabyte_recommended_rank', 'type' => 'integer'],
    ],
    'processor_settings' => [
      'add_url' => [],
      'aggregated_field' => [],
      'custom_value' => [],
      'entity_status' => [],
      'entity_type' => [],
      'impeccabyte_recommended_rank' => [],
      'language_with_fallback' => [],
      'rendered_item' => [],
      'ignorecase' => ['weights' => ['preprocess_index' => -20, 'preprocess_query' => -20], 'all_fields' => TRUE, 'fields' => ['title', 'card_summary']],
      'tokenizer' => ['weights' => ['preprocess_index' => -6, 'preprocess_query' => -6], 'all_fields' => TRUE, 'fields' => ['title', 'card_summary'], 'spaces' => '', 'ignored' => '._-', 'overlap_cjk' => 1, 'minimum_word_size' => '2'],
    ],
    'tracker_settings' => ['default' => ['indexing_order' => 'fifo']],
    'options' => ['cron_limit' => 50, 'delete_on_fail' => TRUE, 'index_directly' => TRUE, 'track_changes_in_references' => TRUE],
  ])->save();
}
$index = Index::load('devices');
$index->indexItems();

// The hardware catalog.
if (!View::load('hardware_catalog')) {
  $facet = fn(string $field, string $identifier, string $label) => [
    'id' => 'facets_' . $field,
    'table' => 'search_api_index_devices',
    'field' => 'facets_' . $field,
    'relationship' => 'none',
    'group_type' => 'group',
    'admin_label' => '',
    'plugin_id' => 'facets_filter',
    'operator' => '=',
    'value' => '',
    'group' => 1,
    'exposed' => TRUE,
    'expose' => ['operator_id' => '', 'label' => $label, 'description' => '', 'use_operator' => FALSE, 'operator' => '', 'operator_limit_selection' => FALSE, 'operator_list' => [], 'identifier' => $identifier, 'required' => FALSE, 'multiple' => TRUE, 'remember' => FALSE, 'remember_roles' => ['authenticated' => 'authenticated']],
    'is_grouped' => FALSE,
    'hierarchy' => FALSE,
    'label_display' => 'visible',
    'facet' => [
      'query_operator' => 'or',
      'min_count' => 1,
      'missing' => FALSE,
      'missing_label' => 'others',
      'show_numbers' => TRUE,
      'processor_configs' => [
        'translate_entity' => ['processor_id' => 'translate_entity', 'weights' => ['build' => 5], 'settings' => []],
        'term_weight_widget_order' => ['processor_id' => 'term_weight_widget_order', 'weights' => ['sort' => 60], 'settings' => ['sort' => 'ASC']],
      ],
    ],
  ];
  $sort = fn(string $field, string $label, string $order) => [
    'id' => $field,
    'table' => 'search_api_index_devices',
    'field' => $field,
    'relationship' => 'none',
    'group_type' => 'group',
    'admin_label' => '',
    'plugin_id' => 'search_api',
    'order' => $order,
    'expose' => ['label' => $label, 'field_identifier' => $field],
    'exposed' => TRUE,
  ];
  $bef_filter = ['plugin_id' => 'bef', 'advanced' => ['sort_options' => FALSE, 'placeholder_text' => '', 'rewrite' => ['filter_rewrite_values' => '', 'filter_rewrite_values_key' => FALSE], 'collapsible' => FALSE, 'collapsible_disable_automatic_open' => FALSE, 'is_secondary' => FALSE, 'hide_label' => FALSE, 'field_classes' => ''], 'select_all_none' => FALSE, 'select_all_none_nested' => FALSE, 'display_inline' => FALSE];
  View::create([
    'id' => 'hardware_catalog',
    'label' => 'Hardware catalog',
    'module' => 'views',
    'description' => 'Device cards with filters, sorting and the compare tray.',
    'tag' => '',
    'base_table' => 'search_api_index_devices',
    'base_field' => 'search_api_id',
    'display' => [
      'default' => [
        'id' => 'default',
        'display_title' => 'Default',
        'display_plugin' => 'default',
        'position' => 0,
        'display_options' => [
          'title' => 'Hardware',
          'fields' => [],
          'pager' => ['type' => 'none', 'options' => ['offset' => 0]],
          'exposed_form' => [
            'type' => 'bef',
            'options' => [
              'submit_button' => 'Apply filters',
              'reset_button' => FALSE,
              'reset_button_label' => 'Clear all',
              'exposed_sorts_label' => 'Sort by',
              'expose_sort_order' => FALSE,
              'sort_asc_label' => 'Asc',
              'sort_desc_label' => 'Desc',
              'text_input_required' => 'Select any filter and click on Apply to see results',
              'text_input_required_format' => 'basic_html',
              'bef' => [
                'general' => ['autosubmit' => TRUE, 'autosubmit_exclude_textfield' => FALSE, 'autosubmit_textfield_delay' => 500, 'autosubmit_hide' => FALSE, 'input_required' => FALSE, 'allow_secondary' => FALSE, 'secondary_label' => 'Advanced options', 'secondary_open' => FALSE, 'reset_button_always_show' => FALSE],
                'sort' => ['plugin_id' => 'default', 'advanced' => ['combine' => FALSE, 'combine_rewrite' => '', 'reset' => FALSE, 'reset_label' => '', 'collapsible' => FALSE, 'collapsible_label' => 'Sort options', 'is_secondary' => FALSE]],
                'filter' => [
                  'facets_device_type' => $bef_filter,
                  'facets_sales_channel' => $bef_filter,
                  'facets_industry' => $bef_filter,
                  'facets_manufacturer' => $bef_filter,
                ],
              ],
            ],
          ],
          'access' => ['type' => 'none', 'options' => []],
          'cache' => ['type' => 'search_api_tag', 'options' => []],
          'empty' => [
            'area_text_custom' => ['id' => 'area_text_custom', 'table' => 'views', 'field' => 'area_text_custom', 'plugin_id' => 'text_custom', 'empty' => TRUE, 'content' => 'No devices match those filters. Clear a filter, or ask us and we will recommend one.', 'tokenize' => FALSE],
          ],
          'sorts' => [
            'recommended_rank' => $sort('recommended_rank', 'Recommended', 'ASC'),
            'lease_price' => $sort('lease_price', 'Monthly: low to high', 'ASC'),
            'buy_price' => $sort('buy_price', 'Price: low to high', 'ASC'),
            'created' => $sort('created', 'Newest', 'DESC'),
          ],
          'arguments' => [],
          'filters' => [
            'facets_device_type' => $facet('device_type', 'type', 'Device type'),
            'facets_sales_channel' => $facet('sales_channel', 'channel', 'Sales channel'),
            'facets_industry' => $facet('industry', 'industry', 'Industry'),
            'facets_manufacturer' => $facet('manufacturer', 'make', 'Manufacturer'),
          ],
          'filter_groups' => ['operator' => 'AND', 'groups' => [1 => 'AND']],
          'style' => ['type' => 'default', 'options' => ['row_class' => '', 'default_row_class' => FALSE, 'uses_fields' => FALSE]],
          'row' => ['type' => 'search_api', 'options' => ['view_modes' => ['entity:node' => ['device' => 'card']]]],
          'query' => ['type' => 'search_api_query', 'options' => ['bypass_access' => FALSE, 'skip_access' => FALSE, 'preserve_facet_query_args' => FALSE, 'query_tags' => []]],
          'relationships' => [],
          'use_ajax' => FALSE,
          'header' => [],
          'footer' => [],
          'display_extenders' => [],
        ],
      ],
      'page_1' => [
        'id' => 'page_1',
        'display_title' => 'Page',
        'display_plugin' => 'page',
        'position' => 1,
        'display_options' => ['display_extenders' => [], 'path' => 'hardware'],
      ],
    ],
  ])->save();
}

// Device cards for page sections: the Home hardware row and the dual pricing
// page's device row. Clover leads through the manufacturer order.
if (!View::load('devices_by_filter')) {
  $boolean = fn(string $field) => ['id' => $field . '_value', 'table' => 'node__' . $field, 'field' => $field . '_value', 'relationship' => 'none', 'group_type' => 'group', 'plugin_id' => 'boolean', 'operator' => '=', 'value' => '1', 'group' => 1, 'exposed' => FALSE];
  $base_filters = [
    'status' => ['id' => 'status', 'table' => 'node_field_data', 'field' => 'status', 'entity_type' => 'node', 'entity_field' => 'status', 'plugin_id' => 'boolean', 'value' => '1', 'group' => 1, 'expose' => ['operator' => ''], 'exposed' => FALSE],
    'type' => ['id' => 'type', 'table' => 'node_field_data', 'field' => 'type', 'entity_type' => 'node', 'entity_field' => 'type', 'plugin_id' => 'bundle', 'operator' => 'in', 'value' => ['device' => 'device'], 'group' => 1, 'exposed' => FALSE],
  ];
  $block = fn(string $id, string $title, string $flag, array $pager, int $position) => [
    'id' => $id,
    'display_title' => $title,
    'display_plugin' => 'block',
    'position' => $position,
    'display_options' => [
      'defaults' => ['filters' => FALSE, 'filter_groups' => FALSE, 'pager' => FALSE],
      'filters' => $base_filters + [$flag . '_value' => $boolean($flag)],
      'filter_groups' => ['operator' => 'AND', 'groups' => [1 => 'AND']],
      'pager' => $pager,
      'block_description' => $title,
      'display_extenders' => [],
    ],
  ];
  View::create([
    'id' => 'devices_by_filter',
    'label' => 'Devices by filter',
    'module' => 'views',
    'description' => 'Feeds the device card grid section when its source is a filter.',
    'tag' => '',
    'base_table' => 'node_field_data',
    'base_field' => 'nid',
    'display' => [
      'default' => [
        'id' => 'default',
        'display_title' => 'Default',
        'display_plugin' => 'default',
        'position' => 0,
        'display_options' => [
          'title' => 'Devices',
          'fields' => [],
          'pager' => ['type' => 'none', 'options' => ['offset' => 0]],
          'exposed_form' => ['type' => 'basic', 'options' => []],
          'access' => ['type' => 'perm', 'options' => ['perm' => 'access content']],
          'cache' => ['type' => 'tag', 'options' => []],
          'empty' => [],
          'sorts' => [
            'weight' => ['id' => 'weight', 'table' => 'taxonomy_term_field_data', 'field' => 'weight', 'relationship' => 'field_manufacturer', 'group_type' => 'group', 'entity_type' => 'taxonomy_term', 'entity_field' => 'weight', 'plugin_id' => 'standard', 'order' => 'ASC', 'exposed' => FALSE],
            'field_sort_weight_value' => ['id' => 'field_sort_weight_value', 'table' => 'node__field_sort_weight', 'field' => 'field_sort_weight_value', 'relationship' => 'none', 'group_type' => 'group', 'plugin_id' => 'standard', 'order' => 'ASC', 'exposed' => FALSE],
          ],
          'arguments' => [],
          'filters' => $base_filters,
          'filter_groups' => ['operator' => 'AND', 'groups' => [1 => 'AND']],
          'style' => ['type' => 'default', 'options' => ['row_class' => '', 'default_row_class' => FALSE, 'uses_fields' => FALSE]],
          'row' => ['type' => 'entity:node', 'options' => ['relationship' => 'none', 'view_mode' => 'teaser']],
          'query' => ['type' => 'views_query', 'options' => ['query_comment' => '', 'disable_sql_rewrite' => FALSE, 'distinct' => FALSE, 'replica' => FALSE, 'query_tags' => []]],
          'relationships' => [
            'field_manufacturer' => ['id' => 'field_manufacturer', 'table' => 'node__field_manufacturer', 'field' => 'field_manufacturer', 'relationship' => 'none', 'group_type' => 'group', 'admin_label' => 'Manufacturer', 'plugin_id' => 'standard', 'required' => FALSE],
          ],
          'header' => [],
          'footer' => [],
          'display_extenders' => [],
        ],
      ],
      'featured' => $block('featured', 'Featured devices', 'field_featured', ['type' => 'some', 'options' => ['offset' => 0, 'items_per_page' => 4]], 1),
      'dual_pricing' => $block('dual_pricing', 'Dual pricing ready devices', 'field_dual_pricing_ready', ['type' => 'none', 'options' => ['offset' => 0]], 2),
    ],
  ])->save();
}

print "The catalog is in place. " . $index->getTrackerInstance()->getIndexedItemsCount() . " devices indexed.\n";
