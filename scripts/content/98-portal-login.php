<?php

/**
 * @file
 * Sends merchants to the merchant portal sign-in (2026-11-26).
 *
 * The app at app.impeccabyte.com now has a merchant portal with its own
 * sign-in at /portal/login; the app's root is the staff sign-in. Every
 * merchant "Log in" on this site moves there: the Merchant app address
 * setting (header and phone menu), the Travel menu's Log In link, and link
 * buttons on pages (travel pages, the "Log in needed" page).
 *
 * Each value is only replaced while it still holds the old address, so
 * anything edited in the admin is kept. On a fresh install the setup and
 * content scripts already use the new address and this script changes
 * nothing.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/98-portal-login.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$old = ['https://app.impeccabyte.com', 'https://app.impeccabyte.com/'];
$new = 'https://app.impeccabyte.com/portal/login';
$changed = [];

// The Merchant app address setting.
$settings = \Drupal::configFactory()->getEditable('impeccabyte_site.settings');
if (in_array($settings->get('login_url'), $old, TRUE)) {
  $settings->set('login_url', $new)->save();
  $changed[] = 'setting login_url';
}

// Menu links and link fields on paragraphs.
$field_map = \Drupal::service('entity_field.manager')->getFieldMapByFieldType('link');
foreach (['menu_link_content', 'paragraph'] as $entity_type) {
  $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
  foreach (array_keys($field_map[$entity_type] ?? []) as $field) {
    $ids = $storage->getQuery()->accessCheck(FALSE)->condition("$field.uri", $old, 'IN')->execute();
    foreach ($storage->loadMultiple($ids) as $entity) {
      $items = $entity->get($field)->getValue();
      foreach ($items as &$item) {
        if (in_array($item['uri'], $old, TRUE)) {
          $item['uri'] = $new;
        }
      }
      unset($item);
      $entity->set($field, $items);
      $entity->save();
      $changed[] = "$entity_type {$entity->id()} $field";
    }
  }
}

print $changed ? 'Updated: ' . implode(', ', $changed) . "\n" : "Nothing to update.\n";
