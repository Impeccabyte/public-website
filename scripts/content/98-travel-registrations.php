<?php

/**
 * @file
 * Fora Travel's registrations and licenses in the travel disclosure
 * (supplied 2026-11-24).
 *
 * The "About our travel services" callout on /travel and
 * /travel/hotel-perks had placeholder registration numbers. It now lists
 * Fora's six: California, Florida, Washington, British Columbia, Ontario and
 * the United Kingdom. California's number is also shown as "CST ...", with
 * the line the state requires.
 *
 * Only a callout that still holds the placeholder text is changed, so an
 * edited disclosure is kept. On a fresh install
 * scripts/content/52-travel.php already has the full text and this script
 * changes nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-travel-registrations.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$old = '<p>Impeccabyte is an independent contractor of Fora Travel, Inc., a registered seller of travel. California CST [number]. Florida [number]. Washington [number]. Registration as a seller of travel does not constitute approval by the State of California.</p>';
$new = '<p>Impeccabyte is an independent contractor of Fora Travel, Inc., a registered seller of travel. Fora Travel, Inc. holds the following registrations and licenses:</p><ul><li>California: Seller of Travel Registration No. 2151995-50 (CST 2151995-50)</li><li>Florida: Seller of Travel Registration No. ST43973</li><li>Washington: Seller of Travel Unified Business ID (UBI) 605329242</li><li>British Columbia: CPBC License No. 88694</li><li>Ontario: TICO Registration No. 50027942</li><li>United Kingdom: ICO Registration No. ZC157277</li></ul><p>Registration as a seller of travel does not constitute approval by the State of California.</p>';

$updated = 0;
foreach (\Drupal::entityTypeManager()->getStorage('paragraph')->loadByProperties(['type' => 'callout', 'field_title' => 'About our travel services']) as $callout) {
  if ($callout->get('field_text')->value === $old) {
    $callout->set('field_text', ['value' => $new, 'format' => $callout->get('field_text')->format])->save();
    $updated++;
  }
}

print "Travel registrations: $updated disclosure(s) updated.\n";
