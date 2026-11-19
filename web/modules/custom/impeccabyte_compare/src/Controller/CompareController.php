<?php

namespace Drupal\impeccabyte_compare\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\impeccabyte_site\Prices;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Renders up to three devices side by side.
 */
class CompareController extends ControllerBase {

  public function __construct(protected Prices $prices) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static($container->get('impeccabyte_site.prices'));
  }

  /**
   * The comparison page for /hardware/compare?ids=1,2,3.
   */
  public function page(Request $request): array {
    $ids = array_slice(array_unique(array_filter(array_map('intval', explode(',', (string) $request->query->get('ids', ''))))), 0, 3);
    $cache = (new CacheableMetadata())
      ->addCacheContexts(['url.query_args:ids'])
      ->addCacheTags(['config:impeccabyte_site.settings', 'node_list:device']);

    $devices = [];
    $columns = [];
    $nodes = $ids ? $this->entityTypeManager()->getStorage('node')->loadMultiple($ids) : [];
    foreach ($ids as $id) {
      $node = $nodes[$id] ?? NULL;
      if (!$node || $node->bundle() !== 'device' || !$node->access('view')) {
        continue;
      }
      $cache->addCacheableDependency($node);
      $devices[] = ['title' => $node->label(), 'url' => $node->toUrl()->toString()];
      $labels = fn(string $field) => array_map(fn($term) => $term->label(), $node->get($field)->referencedEntities());
      $lease = $this->prices->format($node->get('field_lease_price')->value);
      $buy = $this->prices->format($node->get('field_buy_price')->value);
      $columns[] = [
        'manufacturer' => implode(', ', $labels('field_manufacturer')),
        'type' => implode(', ', $labels('field_device_type')),
        'lease' => $lease ? $this->t('From @price/mo', ['@price' => $lease]) : '',
        'buy' => $buy ? $this->t('Buy from @price', ['@price' => $buy]) : '',
        'summary' => $node->get('field_card_summary')->value,
        'channels' => $labels('field_sales_channels'),
        'industries' => $labels('field_industries'),
        'dual' => $node->get('field_dual_pricing_ready')->value ? $this->t('Yes') : $this->t('No'),
        'stock' => $node->get('field_in_stock')->value ? $this->t('In stock') : $this->t('Ask us'),
        'features' => array_column($node->get('field_key_features')->getValue(), 'value'),
      ];
    }

    $definitions = [
      'manufacturer' => [$this->t('Manufacturer'), FALSE],
      'type' => [$this->t('Device type'), FALSE],
      'summary' => [$this->t('In short'), FALSE],
      'lease' => [$this->t('Lease'), TRUE],
      'buy' => [$this->t('Buy'), TRUE],
      'channels' => [$this->t('Where it works'), FALSE],
      'industries' => [$this->t('Good for'), FALSE],
      'dual' => [$this->t('Dual pricing ready'), FALSE],
      'stock' => [$this->t('Availability'), FALSE],
      'features' => [$this->t('Key features'), FALSE],
    ];
    $rows = [];
    foreach ($definitions as $key => [$label, $is_price]) {
      $cells = array_column($columns, $key);
      // A row no device has anything for is left out.
      if (array_filter($cells)) {
        $rows[] = ['label' => $label, 'cells' => $cells, 'is_price' => $is_price];
      }
    }

    $build = [
      '#theme' => 'impeccabyte_compare_table',
      '#devices' => $devices,
      '#rows' => $rows,
      '#catalog_url' => Url::fromUserInput('/hardware')->toString(),
      '#show_prices' => $this->prices->shown(),
      '#price_placeholder' => $this->prices->placeholder(),
      '#quote_url' => Url::fromUserInput($this->prices->quotePath())->toString(),
    ];
    $cache->applyTo($build);
    return $build;
  }

}
