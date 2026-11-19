<?php

namespace Drupal\impeccabyte_site\Plugin\search_api\processor;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\node\NodeInterface;
use Drupal\search_api\Attribute\SearchApiProcessor;
use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the number the "Recommended" sort orders devices by.
 *
 * Manufacturers rank in the order of the Manufacturer vocabulary, where
 * Clover is first, so Clover devices always lead. Within a manufacturer the
 * device's own "Recommended order" decides.
 */
#[SearchApiProcessor(
  id: 'impeccabyte_recommended_rank',
  label: new TranslatableMarkup('Recommended rank'),
  description: new TranslatableMarkup('Ranks devices by manufacturer order, then by their own recommended order.'),
  stages: [
    'add_properties' => 0,
  ],
)]
class RecommendedRank extends ProcessorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(?DatasourceInterface $datasource = NULL): array {
    $properties = [];
    if (!$datasource) {
      $properties['impeccabyte_recommended_rank'] = new ProcessorProperty([
        'label' => $this->t('Recommended rank'),
        'description' => $this->t('Manufacturer order, then the recommended order of the device.'),
        'type' => 'integer',
        'processor_id' => $this->getPluginId(),
      ]);
    }
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item): void {
    $node = $item->getOriginalObject()->getValue();
    if (!$node instanceof NodeInterface || !$node->hasField('field_manufacturer')) {
      return;
    }
    $manufacturer = $node->get('field_manufacturer')->entity;
    $rank = ($manufacturer ? (int) $manufacturer->getWeight() : 99) * 1000 + (int) $node->get('field_sort_weight')->value;
    $fields = $this->getFieldsHelper()->filterForPropertyPath($item->getFields(), NULL, 'impeccabyte_recommended_rank');
    foreach ($fields as $field) {
      $field->addValue($rank);
    }
  }

}
