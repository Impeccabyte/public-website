<?php

/**
 * @file
 * Search titles for the key pages (2026-11-23, docs/seo-keywords.md).
 *
 * Each title carries what people search for (for example "credit card
 * processing austin tx", "pos system for restaurant", "is dual pricing legal
 * in texas"), in sentence case, ending in "| Impeccabyte". The page's own
 * headline is unchanged.
 *
 * A title is only set while the page's Search title is empty, so titles
 * edited in the admin are kept. Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-search-titles.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$titles = [
  '/home' => 'Credit card processing and POS in Austin, TX | Impeccabyte',
  '/pricing' => 'Credit card processing rates and fees | Impeccabyte',
  '/about' => 'About us: an Austin merchant services agency | Impeccabyte',
  '/solutions/in-person' => 'Card terminals and POS systems in Austin | Impeccabyte',
  '/solutions/online' => 'Online payments and payment links | Impeccabyte',
  '/solutions/on-the-go' => 'Mobile card readers and tap to pay | Impeccabyte',
  '/solutions/recurring-billing' => 'Recurring billing for small businesses | Impeccabyte',
  '/solutions/dual-pricing' => 'Dual pricing and cash discount in Texas | Impeccabyte',
  '/solutions/interchange-plus' => 'Interchange plus pricing, explained | Impeccabyte',
  '/solutions/chargeback-protection' => 'Chargeback protection for merchants | Impeccabyte',
  '/solutions/integrations' => 'POS, delivery and accounting integrations | Impeccabyte',
  '/solutions/funding' => 'Merchant funding and deposit times | Impeccabyte',
  '/industries/restaurants' => 'Restaurant POS and card processing in Austin | Impeccabyte',
  '/industries/food-trucks' => 'Food truck POS and card readers in Austin | Impeccabyte',
  '/industries/retail' => 'Retail POS and card processing in Austin | Impeccabyte',
  '/industries/salons-and-spas' => 'Salon and spa POS and card processing | Impeccabyte',
  '/industries/home-services' => 'Card payments for home service pros | Impeccabyte',
  '/industries/healthcare' => 'Card processing for medical and dental offices | Impeccabyte',
  '/industries/auto-repair' => 'Auto repair shop POS and card processing | Impeccabyte',
  '/industries/professional-services' => 'Card payments for professional services | Impeccabyte',
  '/industries/nonprofits' => 'Card processing for nonprofits in Austin | Impeccabyte',
  '/industries/pop-ups-and-markets' => 'Card readers for pop-ups and markets | Impeccabyte',
  '/industries/specialty' => 'High-risk and specialty merchant accounts | Impeccabyte',
];

$set = 0;
foreach ($titles as $alias => $title) {
  $node = ib_node_by_alias($alias);
  if ($node && $node->hasField('field_search_title') && $node->get('field_search_title')->isEmpty()) {
    $node->set('field_search_title', $title)->save();
    $set++;
  }
}

print "Search titles: $set set.\n";
