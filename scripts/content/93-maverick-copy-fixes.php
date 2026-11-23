<?php

/**
 * @file
 * Brings Integrations, Restaurants & Bars and Specialty Industries copy in
 * line with Maverick's knowledge base (read 2026-11-22, summarized in
 * docs/maverick-offerings.md):
 *
 * - Delivery: Clover's restaurant plans include DoorDash, Uber Eats and
 *   Grubhub, so the "order apps" wording goes.
 * - Shopify: Maverick names OpenPath and Checkout Champ, not Authorize.Net
 *   or NMI.
 * - Accounting: the knowledge base names QuickBooks, Xero and FreshBooks
 *   through Chargezoom, Biller Genie and RECUR360. NetSuite, Salesforce and
 *   the ERP names are not in it, so they come off the page.
 * - Adult Content: Maverick's credit matrix prohibits content-creator
 *   platforms, so the hub card no longer says "creators".
 *
 * A value is only replaced while it still holds the old wording, so anything
 * edited in the admin is kept. On a fresh install the source scripts already
 * have the new wording and this script changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/93-maverick-copy-fixes.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
$changed = 0;

// Field => [old value => new value]. Old values are exact.
$replace = [
  'field_body' => [
    'Adult content sites and creators.' => 'Adult content websites.',
    'Sales, fees and invoices synced to QuickBooks, Xero, NetSuite or Salesforce.' => 'Sales, fees and invoices synced to QuickBooks, Xero or FreshBooks.',
    'DoorDash connects directly to Clover, so delivery orders land on your POS and print in the kitchen next to everything else. Uber Eats, Grubhub and others connect through order apps that send every order to the same screen, so you can retire the stack of tablets.' => "On Clover's restaurant plans, DoorDash, Uber Eats and Grubhub connect straight to your POS, so delivery orders land on the same screen and print in the kitchen next to everything else. You can retire the stack of tablets.",
    'DoorDash connects directly to Clover, so delivery orders print in your kitchen with everything else. Uber Eats and Grubhub connect through order apps that do the same.' => "DoorDash, Uber Eats and Grubhub connect to Clover's restaurant plans, so delivery orders print in your kitchen with everything else.",
    'Authorize.Net, NMI and accept.blue all have ready-made plugins for the big shopping carts. We match the gateway to your cart, connect it to your merchant account and run a test order with you.' => 'Maverick works with checkout partners and gateways that have ready-made plugins for the big shopping carts, such as OpenPath and Checkout Champ for Shopify, and Valor and iPOSPays for WooCommerce. We match the plugin to your cart, connect it to your merchant account and run a test order with you.',
    'Connect your payments to accounting, invoicing and CRM software through your gateway or apps built for Maverick accounts, so payments, fees and invoices match up without retyping.' => 'Connect your payments to your accounting and invoicing software through apps built for Maverick accounts, such as Chargezoom, Biller Genie and RECUR360, so payments, fees and invoices match up without retyping.',
  ],
  'field_title' => [
    'Accounting and CRM' => 'Accounting',
  ],
  'field_footnote' => [
    'Delivery services and order apps charge their own fees. Names and logos belong to their owners.' => 'Delivery services charge their own fees. Names and logos belong to their owners.',
  ],
  'field_note' => [
    'Sage, Acumatica and Dynamics can connect too. Ask us.' => "Tell us which one and we'll check whether it connects.",
  ],
  'field_answer' => [
    '<p>They connect through order-management apps that collect orders from every delivery service and send them to your Clover. Those apps have their own monthly fee, which we include in your quote.</p>' => "<p>They connect to Clover too. Clover's restaurant plans include Uber Eats, DoorDash and Grubhub, so orders from each show on your POS. Each delivery service charges its own fees on its orders.</p>",
    "<p>Yes, through a gateway plugin such as Authorize.Net or NMI. Shopify may charge an extra fee on sales that don't use Shopify Payments, so we compare the total cost with you before you switch.</p>" => "<p>Yes, through a checkout partner Maverick works with, such as OpenPath or Checkout Champ. Shopify may charge an extra fee on sales that don't use Shopify Payments, so we compare the total cost with you before you switch.</p>",
    "<p>It can. Several gateways and partner apps sync payments and fees to QuickBooks Online and Desktop. Tell us which version you use and we'll recommend the simplest route.</p>" => "<p>It can. Partner apps such as Chargezoom, Biller Genie and RECUR360 sync payments to QuickBooks Online and Desktop, and the Valor and iPOSPays portals sync to QuickBooks Online. Tell us which version you use and we'll recommend the simplest route.</p>",
    "<p>Ask us. Gateways connect to hundreds of carts and business tools, and Maverick works with certified software partners. If something won't connect, we'll tell you before you sign.</p>" => "<p>Ask us. Maverick works with certified software partners, and many tools connect through a gateway. If something won't connect, we'll tell you before you sign.</p>",
  ],
];

foreach ($replace as $field => $pairs) {
  foreach ($pairs as $old => $new) {
    $ids = $storage->getQuery()->accessCheck(FALSE)->condition($field . '.value', $old)->execute();
    foreach ($storage->loadMultiple($ids) as $paragraph) {
      if ($paragraph->get($field)->value !== $old) {
        continue;
      }
      $paragraph->get($field)->value = $new;
      $paragraph->save();
      $changed++;
    }
  }
}

// Drop the NetSuite and Salesforce logos from the accounting logo strip.
$drop = \Drupal::entityTypeManager()->getStorage('media')->getQuery()->accessCheck(FALSE)
  ->condition('bundle', 'image')
  ->condition('name', ['Integration logo: NetSuite', 'Integration logo: Salesforce'], 'IN')
  ->execute();
if ($drop) {
  $ids = $storage->getQuery()->accessCheck(FALSE)->condition('type', 'logo_strip')->condition('field_logos.target_id', array_values($drop), 'IN')->execute();
  foreach ($storage->loadMultiple($ids) as $paragraph) {
    $keep = array_values(array_filter($paragraph->get('field_logos')->getValue(), fn(array $item) => !in_array($item['target_id'], $drop)));
    $paragraph->set('field_logos', $keep);
    $paragraph->save();
    $changed++;
  }
}

echo "Maverick copy fixes: $changed value(s) updated.\n";
