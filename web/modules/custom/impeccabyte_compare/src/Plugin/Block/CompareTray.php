<?php

namespace Drupal\impeccabyte_compare\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;

/**
 * The sticky tray that lists the devices picked for comparison.
 */
#[Block(
  id: 'impeccabyte_compare_tray',
  admin_label: new TranslatableMarkup('Compare tray'),
  category: new TranslatableMarkup('Impeccabyte'),
)]
class CompareTray extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      '#theme' => 'impeccabyte_compare_tray',
      '#max' => 3,
      '#compare_url' => Url::fromRoute('impeccabyte_compare.page')->toString(),
    ];
  }

}
