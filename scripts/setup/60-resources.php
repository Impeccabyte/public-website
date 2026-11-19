<?php

/**
 * @file
 * Phase 6, resources: the resource content type's fields and the resource
 * library view with its type and topic filters and keyword search.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/60-resources.php
 * then export config.
 */

use Drupal\views\Entity\View;

require_once __DIR__ . '/_helpers.php';

$n = 'node';
$b = 'resource';
ib_field($n, $b, 'field_resource_type', 'entity_reference', 'Type', ['storage' => ['target_type' => 'taxonomy_term'], 'settings' => ib_ref_terms('resource_type'), 'required' => TRUE, 'widget' => 'options_select', 'weight' => 0]);
ib_field($n, $b, 'field_resource_topic', 'entity_reference', 'Topic', ['storage' => ['target_type' => 'taxonomy_term'], 'settings' => ib_ref_terms('resource_topic'), 'required' => TRUE, 'widget' => 'options_select', 'weight' => 1]);
ib_field($n, $b, 'field_summary', 'string_long', 'Summary', ['required' => TRUE, 'weight' => 2, 'description' => 'One or two sentences for cards and the top of the page.']);
ib_field($n, $b, 'field_meta', 'string', 'Read time or format', ['weight' => 3, 'description' => 'For example 8 min read, PDF checklist or 2 min video.']);
ib_field($n, $b, 'field_thumbnail', 'entity_reference', 'Thumbnail', ['storage' => ['target_type' => 'media'], 'settings' => ib_ref_media(['image']), 'widget' => 'media_library_widget', 'weight' => 4]);
ib_field($n, $b, 'field_body', 'text_long', 'Body', ['weight' => 5, 'settings' => ['allowed_formats' => ['basic_html', 'full_html']]]);
ib_field($n, $b, 'field_file', 'entity_reference', 'File', ['storage' => ['target_type' => 'media'], 'settings' => ib_ref_media(['document']), 'widget' => 'media_library_widget', 'weight' => 6, 'description' => 'A download, such as a PDF checklist.']);
ib_field($n, $b, 'field_featured', 'boolean', 'Featured', ['weight' => 7, 'widget' => 'boolean_checkbox', 'widget_settings' => ['display_label' => TRUE], 'description' => 'The featured resource shows above the library grid. Feature one at a time.']);
ib_field($n, $b, 'field_placeholder', 'boolean', 'This is a placeholder', ['weight' => 8, 'widget' => 'boolean_checkbox', 'widget_settings' => ['display_label' => TRUE], 'description' => 'For customer stories that are not real, approved stories yet. Placeholders are labeled on the page.']);

if (!View::load('resource_library')) {
  $term_filter = fn(string $field, string $vid, string $identifier, string $label) => [
    'id' => $field . '_target_id',
    'table' => 'node__' . $field,
    'field' => $field . '_target_id',
    'relationship' => 'none',
    'group_type' => 'group',
    'plugin_id' => 'taxonomy_index_tid',
    'operator' => 'or',
    'value' => [],
    'group' => 1,
    'exposed' => TRUE,
    'expose' => ['operator_id' => $field . '_target_id_op', 'label' => $label, 'description' => '', 'use_operator' => FALSE, 'operator' => $field . '_target_id_op', 'operator_limit_selection' => FALSE, 'operator_list' => [], 'identifier' => $identifier, 'required' => FALSE, 'remember' => FALSE, 'multiple' => FALSE, 'remember_roles' => ['authenticated' => 'authenticated'], 'reduce' => FALSE],
    'is_grouped' => FALSE,
    'reduce_duplicates' => FALSE,
    'vid' => $vid,
    'type' => 'select',
    'hierarchy' => FALSE,
    'limit' => TRUE,
    'error_message' => TRUE,
  ];
  $hidden_field = fn(string $id, string $table, string $field, array $more = []) => ['id' => $id, 'table' => $table, 'field' => $field, 'relationship' => 'none', 'group_type' => 'group', 'label' => '', 'exclude' => TRUE, 'element_label_colon' => FALSE] + $more;
  View::create([
    'id' => 'resource_library',
    'label' => 'Resource library',
    'module' => 'views',
    'description' => 'Resource cards with type and topic filters and keyword search.',
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
          'title' => 'Resources',
          // Hidden fields the keyword search reads.
          'fields' => [
            'title' => $hidden_field('title', 'node_field_data', 'title', ['entity_type' => 'node', 'entity_field' => 'title', 'plugin_id' => 'field', 'type' => 'string', 'settings' => ['link_to_entity' => FALSE]]),
            'field_summary' => $hidden_field('field_summary', 'node__field_summary', 'field_summary', ['plugin_id' => 'field', 'type' => 'basic_string']),
          ],
          'pager' => ['type' => 'none', 'options' => ['offset' => 0]],
          'exposed_form' => [
            'type' => 'bef',
            'options' => [
              'submit_button' => 'Search',
              'reset_button' => FALSE,
              'reset_button_label' => 'Reset',
              'exposed_sorts_label' => 'Sort by',
              'expose_sort_order' => FALSE,
              'sort_asc_label' => 'Asc',
              'sort_desc_label' => 'Desc',
              'bef' => ['general' => ['autosubmit' => FALSE, 'input_required' => FALSE, 'allow_secondary' => FALSE]],
            ],
          ],
          'access' => ['type' => 'perm', 'options' => ['perm' => 'access content']],
          'cache' => ['type' => 'tag', 'options' => []],
          'empty' => [
            'area_text_custom' => ['id' => 'area_text_custom', 'table' => 'views', 'field' => 'area_text_custom', 'plugin_id' => 'text_custom', 'empty' => TRUE, 'content' => 'Nothing matches that yet. Try another word or clear a filter.', 'tokenize' => FALSE],
          ],
          'sorts' => [
            'created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'relationship' => 'none', 'group_type' => 'group', 'entity_type' => 'node', 'entity_field' => 'created', 'plugin_id' => 'date', 'order' => 'DESC', 'exposed' => FALSE, 'granularity' => 'second'],
            'nid' => ['id' => 'nid', 'table' => 'node_field_data', 'field' => 'nid', 'relationship' => 'none', 'group_type' => 'group', 'entity_type' => 'node', 'entity_field' => 'nid', 'plugin_id' => 'standard', 'order' => 'ASC', 'exposed' => FALSE],
          ],
          'arguments' => [],
          'filters' => [
            'status' => ['id' => 'status', 'table' => 'node_field_data', 'field' => 'status', 'entity_type' => 'node', 'entity_field' => 'status', 'plugin_id' => 'boolean', 'value' => '1', 'group' => 1, 'expose' => ['operator' => ''], 'exposed' => FALSE],
            'type' => ['id' => 'type', 'table' => 'node_field_data', 'field' => 'type', 'entity_type' => 'node', 'entity_field' => 'type', 'plugin_id' => 'bundle', 'operator' => 'in', 'value' => ['resource' => 'resource'], 'group' => 1, 'exposed' => FALSE],
            // The featured resource has its own spot above the grid.
            'field_featured_value' => ['id' => 'field_featured_value', 'table' => 'node__field_featured', 'field' => 'field_featured_value', 'relationship' => 'none', 'group_type' => 'group', 'plugin_id' => 'boolean', 'operator' => '!=', 'value' => '1', 'group' => 1, 'exposed' => FALSE],
            'field_resource_type_target_id' => $term_filter('field_resource_type', 'resource_type', 'type', 'Type'),
            'field_resource_topic_target_id' => $term_filter('field_resource_topic', 'resource_topic', 'topic', 'Topic'),
            'combine' => [
              'id' => 'combine',
              'table' => 'views',
              'field' => 'combine',
              'relationship' => 'none',
              'group_type' => 'group',
              'plugin_id' => 'combine',
              'operator' => 'allwords',
              'value' => '',
              'group' => 1,
              'exposed' => TRUE,
              'expose' => ['operator_id' => 'combine_op', 'label' => 'Search resources', 'description' => '', 'use_operator' => FALSE, 'operator' => 'combine_op', 'operator_limit_selection' => FALSE, 'operator_list' => [], 'identifier' => 'search', 'required' => FALSE, 'remember' => FALSE, 'multiple' => FALSE, 'remember_roles' => ['authenticated' => 'authenticated'], 'placeholder' => 'Try “chargeback” or “dual pricing”'],
              'is_grouped' => FALSE,
              'fields' => ['title' => 'title', 'field_summary' => 'field_summary'],
            ],
          ],
          'filter_groups' => ['operator' => 'AND', 'groups' => [1 => 'AND']],
          'style' => ['type' => 'default', 'options' => ['row_class' => '', 'default_row_class' => FALSE, 'uses_fields' => FALSE]],
          'row' => ['type' => 'entity:node', 'options' => ['relationship' => 'none', 'view_mode' => 'card']],
          'query' => ['type' => 'views_query', 'options' => ['query_comment' => '', 'disable_sql_rewrite' => FALSE, 'distinct' => TRUE, 'replica' => FALSE, 'query_tags' => []]],
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
        'display_options' => ['display_extenders' => [], 'path' => 'resources'],
      ],
    ],
  ])->save();
}

print "Resources are in place.\n";
