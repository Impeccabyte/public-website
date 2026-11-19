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

use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\system\Entity\Menu;

if (!function_exists('ib_menu')) {

  function ib_menu(string $id, string $label, string $description = ''): void {
    if (!Menu::load($id)) {
      Menu::create(['id' => $id, 'label' => $label, 'description' => $description])->save();
    }
  }

  /**
   * Creates a menu link, or updates the one with the same title and parent.
   *
   * Options: parent (a link returned by this function), description, icon,
   * weight. Returns the link so it can be used as a parent.
   */
  function ib_menu_link(string $menu, string $title, string $uri, array $options = []): MenuLinkContent {
    $options += ['parent' => NULL, 'description' => '', 'icon' => '', 'weight' => 0];
    $parent_id = $options['parent'] ? $options['parent']->getPluginId() : '';
    $storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');
    $query = $storage->getQuery()->accessCheck(FALSE)->condition('menu_name', $menu)->condition('title', $title);
    $parent_id ? $query->condition('parent', $parent_id) : $query->notExists('parent');
    $ids = $query->execute();
    if (!$ids && !$parent_id) {
      // A top-level link may have been saved with an empty string parent.
      $ids = $storage->getQuery()->accessCheck(FALSE)->condition('menu_name', $menu)->condition('title', $title)->condition('parent', '')->execute();
    }
    /** @var \Drupal\menu_link_content\Entity\MenuLinkContent $link */
    $link = $ids ? $storage->load(reset($ids)) : MenuLinkContent::create(['menu_name' => $menu, 'title' => $title, 'expanded' => TRUE]);
    $before = [$link->get('link')->uri, $link->getDescription(), $link->hasField('field_icon') ? $link->get('field_icon')->value : NULL, (int) $link->getWeight()];
    $link->set('link', ['uri' => $uri]);
    $link->set('description', $options['description']);
    $link->set('weight', $options['weight']);
    if ($parent_id) {
      $link->set('parent', $parent_id);
    }
    if ($link->hasField('field_icon')) {
      $link->set('field_icon', $options['icon']);
    }
    $after = [$uri, $options['description'], $link->hasField('field_icon') ? $options['icon'] : NULL, (int) $options['weight']];
    if ($link->isNew() || $before !== $after) {
      $link->save();
    }
    return $link;
  }

}

use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

if (!function_exists('ib_paragraph')) {

  /**
   * Creates a paragraph from a short spec and returns its field value.
   *
   * Keys are field names without "field_". A list of arrays that each carry
   * "_type" becomes child paragraphs. Example:
   *   ib_paragraph('steps', ['heading' => 'How it works', 'items' => [
   *     ['_type' => 'step', 'title' => 'Apply', 'body' => '...'],
   *   ]]);
   */
  function ib_paragraph(string $type, array $spec): array {
    $values = ['type' => $type];
    foreach ($spec as $key => $value) {
      if (is_array($value) && $value && is_array(reset($value)) && isset(reset($value)['_type'])) {
        $children = [];
        foreach ($value as $child) {
          $child_type = $child['_type'];
          unset($child['_type']);
          $children[] = ib_paragraph($child_type, $child);
        }
        $value = $children;
      }
      $values['field_' . $key] = $value;
    }
    $paragraph = Paragraph::create($values);
    $paragraph->save();
    return ['target_id' => $paragraph->id(), 'target_revision_id' => $paragraph->getRevisionId()];
  }

  /**
   * Finds the node that owns a URL alias.
   */
  function ib_node_by_alias(string $alias): ?Node {
    $aliases = \Drupal::entityTypeManager()->getStorage('path_alias')->loadByProperties(['alias' => $alias]);
    foreach ($aliases as $entity) {
      if (preg_match('#^/node/(\d+)$#', $entity->getPath(), $match) && ($node = Node::load($match[1]))) {
        return $node;
      }
    }
    return NULL;
  }

  /**
   * Creates a published page at an alias unless one is already there.
   *
   * $sections is a list of [type, spec] pairs for ib_paragraph(). $fields
   * holds any other field values, keyed by full field name.
   */
  function ib_page(string $bundle, string $title, string $alias, array $sections = [], array $fields = []): Node {
    if ($node = ib_node_by_alias($alias)) {
      return $node;
    }
    $values = ['type' => $bundle, 'title' => $title, 'status' => 1, 'uid' => 1, 'path' => ['alias' => $alias, 'pathauto' => 0]] + $fields;
    if ($sections) {
      $values['field_sections'] = array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections);
    }
    $node = Node::create($values);
    $node->save();
    return $node;
  }

  function ib_link(string $path, string $title): array {
    return ['uri' => str_contains($path, '://') ? $path : 'internal:' . $path, 'title' => $title];
  }

  function ib_html(string $html): array {
    return ['value' => $html, 'format' => 'basic_html'];
  }

}
