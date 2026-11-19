<?php

namespace Drupal\impeccabyte_site\Twig;

use Drupal\impeccabyte_site\Prices;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig helpers for prices.
 */
class SiteExtension extends AbstractExtension {

  public function __construct(protected Prices $prices) {}

  /**
   * {@inheritdoc}
   */
  public function getFunctions(): array {
    return [
      new TwigFunction('ib_show_prices', fn(): bool => $this->prices->shown()),
      new TwigFunction('ib_price_placeholder', fn(): string => $this->prices->placeholder()),
      new TwigFunction('ib_quote_path', fn(): string => $this->prices->quotePath()),
      new TwigFunction('ib_money', fn($amount): string => $this->prices->format($amount)),
    ];
  }

}
