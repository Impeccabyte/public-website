<?php

/**
 * @file
 * Helpers shared by the setup and content scripts.
 *
 * Every helper leaves existing things as they are, so the scripts can be run
 * again at any time. Include with: require_once __DIR__ . '/_helpers.php';
 */

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\NodeType;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\pathauto\Entity\PathautoPattern;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;

if (!function_exists('ib_vocabulary')) {

  function ib_vocabulary(string $vid, string $name, string $description): void {
    if (!Vocabulary::load($vid)) {
      Vocabulary::create(['vid' => $vid, 'name' => $name, 'description' => $description])->save();
    }
  }

  /**
   * Finds a term by name in a vocabulary.
   */
  function ib_term_id(string $vid, string $name): ?int {
    $ids = \Drupal::entityQuery('taxonomy_term')->accessCheck(FALSE)->condition('vid', $vid)->condition('name', $name)->range(0, 1)->execute();
    return $ids ? (int) reset($ids) : NULL;
  }

  /**
   * Creates a term unless one with that name exists. Returns its id.
   */
  function ib_term(string $vid, string $name, int $weight = 0, ?string $parent = NULL, array $values = []): int {
    if ($tid = ib_term_id($vid, $name)) {
      return $tid;
    }
    $term = Term::create(['vid' => $vid, 'name' => $name, 'weight' => $weight] + $values);
    if ($parent && ($parent_id = ib_term_id($vid, $parent))) {
      $term->set('parent', $parent_id);
    }
    $term->save();
    return (int) $term->id();
  }

  function ib_node_type(string $type, string $name, string $description): void {
    if (!NodeType::load($type)) {
      NodeType::create(['type' => $type, 'name' => $name, 'description' => $description, 'new_revision' => TRUE, 'display_submitted' => FALSE, 'preview_mode' => 0])->save();
    }
  }

  function ib_paragraph_type(string $id, string $label, string $description = ''): void {
    if (!ParagraphsType::load($id)) {
      ParagraphsType::create(['id' => $id, 'label' => $label, 'description' => $description])->save();
    }
  }

  /**
   * Adds a field to a bundle, with its form widget and view formatter.
   *
   * Options: storage, settings, cardinality, required, description, default,
   * widget, widget_settings, formatter, formatter_settings, weight, hide.
   */
  function ib_field(string $entity_type, string $bundle, string $name, string $type, string $label, array $options = []): void {
    $options += [
      'storage' => [],
      'settings' => [],
      'cardinality' => 1,
      'required' => FALSE,
      'description' => '',
      'default' => NULL,
      'widget' => NULL,
      'widget_settings' => [],
      'formatter' => NULL,
      'formatter_settings' => [],
      'weight' => 0,
      'hide' => FALSE,
    ];
    if (!FieldStorageConfig::loadByName($entity_type, $name)) {
      FieldStorageConfig::create([
        'field_name' => $name,
        'entity_type' => $entity_type,
        'type' => $type,
        'settings' => $options['storage'],
        'cardinality' => $options['cardinality'],
      ])->save();
    }
    if (FieldConfig::loadByName($entity_type, $bundle, $name)) {
      return;
    }
    $config = [
      'field_name' => $name,
      'entity_type' => $entity_type,
      'bundle' => $bundle,
      'label' => $label,
      'description' => $options['description'],
      'required' => $options['required'],
      'settings' => $options['settings'],
    ];
    if ($options['default'] !== NULL) {
      $config['default_value'] = [['value' => $options['default']]];
    }
    FieldConfig::create($config)->save();

    $display = \Drupal::service('entity_display.repository');
    $form = ['weight' => $options['weight']];
    if ($options['widget']) {
      $form['type'] = $options['widget'];
      $form['settings'] = $options['widget_settings'];
    }
    $display->getFormDisplay($entity_type, $bundle)->setComponent($name, $form)->save();
    $view_display = $display->getViewDisplay($entity_type, $bundle);
    if ($options['hide']) {
      $view_display->removeComponent($name)->save();
    }
    else {
      $view = ['label' => 'hidden', 'weight' => $options['weight']];
      if ($options['formatter']) {
        $view['type'] = $options['formatter'];
        $view['settings'] = $options['formatter_settings'];
      }
      $view_display->setComponent($name, $view)->save();
    }
  }

  function ib_ref_terms(string $vid): array {
    return ['handler' => 'default:taxonomy_term', 'handler_settings' => ['target_bundles' => [$vid => $vid], 'auto_create' => FALSE]];
  }

  function ib_ref_nodes(array $bundles): array {
    return ['handler' => 'default:node', 'handler_settings' => ['target_bundles' => array_combine($bundles, $bundles)]];
  }

  function ib_ref_media(array $bundles): array {
    return ['handler' => 'default:media', 'handler_settings' => ['target_bundles' => array_combine($bundles, $bundles)]];
  }

  function ib_ref_paragraphs(array $types): array {
    return ['handler' => 'default:paragraph', 'handler_settings' => ['target_bundles' => array_combine($types, $types), 'negate' => 0]];
  }

  /**
   * Adds a paragraphs field (entity reference revisions).
   */
  function ib_paragraphs_field(string $entity_type, string $bundle, string $name, string $label, array $types, int $cardinality = -1, int $weight = 0, string $description = ''): void {
    ib_field($entity_type, $bundle, $name, 'entity_reference_revisions', $label, [
      'storage' => ['target_type' => 'paragraph'],
      'settings' => ib_ref_paragraphs($types),
      'cardinality' => $cardinality,
      'widget' => 'paragraphs',
      'widget_settings' => ['title' => 'Section', 'title_plural' => 'Sections', 'edit_mode' => 'closed', 'closed_mode' => 'summary', 'add_mode' => 'dropdown', 'form_display_mode' => 'default', 'default_paragraph_type' => '_none'],
      'formatter' => 'entity_reference_revisions_entity_view',
      'formatter_settings' => ['view_mode' => 'default'],
      'weight' => $weight,
      'description' => $description,
    ]);
  }

  /**
   * Adds a URL alias pattern for one node type or vocabulary.
   */
  function ib_pathauto(string $id, string $label, string $pattern, string $entity_type, string $bundle, int $weight = 0): void {
    if (PathautoPattern::load($id)) {
      return;
    }
    $entity = PathautoPattern::create(['id' => $id, 'label' => $label, 'type' => 'canonical_entities:' . $entity_type, 'pattern' => $pattern, 'weight' => $weight]);
    $entity->addSelectionCondition([
      'id' => 'entity_bundle:' . $entity_type,
      'bundles' => [$bundle => $bundle],
      'negate' => FALSE,
      'context_mapping' => [$entity_type => $entity_type],
    ]);
    $entity->save();
  }

}
