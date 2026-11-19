<?php

namespace Drupal\impeccabyte_site;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Answers whether prices are shown, and formats them when they are.
 *
 * Pricing is not set yet, so the "Show prices" setting ships off. While it is
 * off every price on the site renders as the placeholder with a quote link.
 */
class Prices {

  public function __construct(protected ConfigFactoryInterface $configFactory) {}

  /**
   * Whether real prices are shown.
   */
  public function shown(): bool {
    return (bool) $this->configFactory->get('impeccabyte_site.settings')->get('show_prices');
  }

  /**
   * The text shown in place of a price.
   */
  public function placeholder(): string {
    return (string) ($this->configFactory->get('impeccabyte_site.settings')->get('price_placeholder') ?: 'Pricing coming soon');
  }

  /**
   * The path of the quote form.
   */
  public function quotePath(): string {
    return (string) ($this->configFactory->get('impeccabyte_site.settings')->get('quote_path') ?: '/get-a-quote');
  }

  /**
   * Formats a dollar amount: whole dollars lose their cents.
   */
  public function format(float|int|string|null $amount): string {
    if ($amount === NULL || $amount === '') {
      return '';
    }
    $amount = (float) $amount;
    $decimals = fmod($amount, 1.0) === 0.0 ? 0 : 2;
    return '$' . number_format($amount, $decimals, '.', ',');
  }

}
