<?php

/**
 * @file
 * Views hooks for the Impeccabyte site module.
 */

/**
 * Implements hook_views_post_execute().
 *
 * The catalog's price sorts would put devices without a price first, since
 * the search index sorts an empty price as the lowest. Move them to the end
 * instead, keeping each group's order. The catalog has no pager, so every
 * device is in this result.
 */
function impeccabyte_site_views_post_execute(\Drupal\views\ViewExecutable $view) {
  $fields = ['lease_price' => 'field_lease_price', 'buy_price' => 'field_buy_price'];
  $sort = $view->getExposedInput()['sort_by'] ?? '';
  if ($view->id() !== 'hardware_catalog' || !isset($fields[$sort])) {
    return;
  }
  $priced = $unpriced = [];
  foreach ($view->result as $row) {
    $object = $row->_object ?? NULL;
    $entity = $object instanceof \Drupal\Core\TypedData\TypedDataInterface ? $object->getValue() : ($row->_entity ?? NULL);
    $empty = $entity instanceof \Drupal\Core\Entity\FieldableEntityInterface && $entity->hasField($fields[$sort]) && $entity->get($fields[$sort])->isEmpty();
    if ($empty) {
      $unpriced[] = $row;
    }
    else {
      $priced[] = $row;
    }
  }
  $view->result = array_values(array_merge($priced, $unpriced));
  foreach ($view->result as $index => $row) {
    $row->index = $index;
  }
}
