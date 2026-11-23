<?php

/**
 * @file
 * Official product photos for every device, from
 * scripts/content/assets/devices (see the README there for sources).
 *
 * Creates one image media item per photo and sets it as the device's
 * gallery. The first photo is the catalog card image.
 *
 * Safe to re-run: a photo that already exists is reused, and a device whose
 * gallery already holds real photos is left alone. A gallery made only of
 * the old placeholder drawings is replaced, and those drawings are deleted
 * once nothing uses them. Run it with
 *   ./scripts/drush php:script scripts/content/92-device-photos.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Alias => [file, view label, alt text], lead image first.
$photos = [
  'clover-mini' => [
    ['clover-mini-1-angled.webp', 'angled view', 'Clover Mini, a white countertop POS with an 8-inch screen showing a register of menu items, seen from the left.'],
    ['clover-mini-2-front.webp', 'front view', 'Clover Mini from the front, its screen showing a paid order and receipt options.'],
  ],
  'clover-flex' => [
    ['clover-flex-1-angled.webp', 'angled view', 'Clover Flex handheld tilted up, its screen showing an order with a total.'],
    ['clover-flex-2-front.webp', 'front view', 'Clover Flex handheld from the front, showing a payment screen.'],
  ],
  'clover-station-duo' => [
    ['clover-station-duo-1-front.webp', 'full setup', 'Clover Station Duo: the staff touchscreen with a receipt printer and cash drawer, and the customer-facing payment screen beside it.'],
  ],
  'clover-station-solo' => [
    ['clover-station-solo-1-merchant.webp', 'staff side', 'Clover Station Solo from the staff side: a large touchscreen register beside its receipt printer.'],
    ['clover-station-solo-2-front.webp', 'full setup', 'Clover Station Solo with its cash drawer and receipt printer.'],
  ],
  'clover-compact' => [
    ['clover-compact-1-angled.webp', 'angled view', 'Clover Compact, a small white countertop terminal with a keypad, showing a tap-to-pay screen.'],
    ['clover-compact-2-front.webp', 'front view', 'Clover Compact from the front, showing a tap-to-pay screen.'],
  ],
  'clover-go' => [
    ['clover-go-1-front.webp', 'front view', 'Clover Go, a small white pocket card reader with the contactless symbol.'],
  ],
  'clover-kiosk' => [
    ['clover-kiosk-1-front.webp', 'front view', 'Clover Kiosk, a tall self-order screen showing menu items, with a payment terminal below.'],
  ],
  'dejavoo-z8' => [
    ['dejavoo-z8-1-angled.webp', 'angled view', 'Dejavoo Z8, a black countertop terminal with a keypad and receipt printer, showing an amount to pay.'],
  ],
  'dejavoo-qd4' => [
    ['dejavoo-qd4-1-front.webp', 'front view', 'Dejavoo QD4 in black from the front, its touchscreen asking the customer to tap, insert or swipe.'],
    ['dejavoo-qd4-2-angled.webp', 'angled view', 'Dejavoo QD4 in black at an angle, showing its printer housing and large touchscreen.'],
    ['dejavoo-qd4-3-white.webp', 'white finish', 'Dejavoo QD4 in white at an angle.'],
  ],
  'pax-a920-pro' => [
    ['pax-a920-pro-1-angled.webp', 'angled view', 'PAX A920 Pro handheld at an angle, its touchscreen showing payment apps.'],
    ['pax-a920-pro-2-front.webp', 'front view', 'PAX A920 Pro handheld from the front.'],
    ['pax-a920-pro-3-side.webp', 'side view', 'PAX A920 Pro lying flat, showing its built-in printer at the top.'],
  ],
  'valor-vp550' => [
    ['valor-vp550-1-front.webp', 'front view', 'Valor VP550, a white Android terminal with a printer on top, showing an amount entry keypad.'],
    ['valor-vp550-2-signature.webp', 'signature screen', 'Valor VP550 showing a tip and signature screen.'],
  ],
  'swipesimple-b350' => [
    ['swipesimple-b350-1-angled.webp', 'angled view', 'SwipeSimple B350, a small black card reader with the contactless symbol, tilted to the side.'],
    ['swipesimple-b350-2-front.webp', 'front view', 'SwipeSimple B350 card reader from the front.'],
  ],
  // Added from Maverick's terminal lineup (31-maverick-devices.php).
  'dejavoo-qd2' => [
    ['dejavoo-qd2-1-angled.webp', 'angled view', 'Dejavoo QD2 in black, a wireless Android handheld with a printer housing, at an angle.'],
    ['dejavoo-qd2-2-white.webp', 'white finish', 'Dejavoo QD2 in white at an angle.'],
  ],
  'dejavoo-qd3' => [
    ['dejavoo-qd3-1-front.webp', 'front view', 'Dejavoo QD3, a compact black Android payment device, from the front.'],
  ],
  'dejavoo-p1' => [
    ['dejavoo-p1-1-front.webp', 'front view', 'Dejavoo P1, a black countertop terminal with a touchscreen above a keypad.'],
  ],
  'dejavoo-p3' => [
    ['dejavoo-p3-1-angled.webp', 'angled view', 'Dejavoo P3, a rugged black wireless terminal with a touchscreen and keypad, at an angle.'],
    ['dejavoo-p3-2-front.webp', 'front view', 'Dejavoo P3 from the front.'],
  ],
  'dejavoo-p5' => [
    ['dejavoo-p5-1-front.webp', 'front view', 'Dejavoo P5, a compact black touchscreen payment device.'],
    ['dejavoo-p5-2-dual-pricing.webp', 'dual pricing screen', 'Dejavoo P5 showing a cash price and a card price.'],
  ],
  'dejavoo-p8' => [
    ['dejavoo-p8-1-front.webp', 'front view', 'Dejavoo P8, a lightweight black touchscreen handheld.'],
    ['dejavoo-p8-2-amount.webp', 'amount entry', 'Dejavoo P8 showing an amount entry keypad on screen.'],
  ],
  'dejavoo-p18' => [
    ['dejavoo-p18-1-docked.webp', 'on its base', 'Dejavoo P18, a black tablet terminal on its countertop base.'],
  ],
  'valor-vp100' => [
    ['valor-vp100-1-front.webp', 'front view', 'Valor VP100, a white countertop terminal with a keypad, showing a tip screen.'],
    ['valor-vp100-2-signature.webp', 'signature screen', 'Valor VP100 showing a signature screen.'],
  ],
  'valor-vp800' => [
    ['valor-vp800-1-front.webp', 'front view', 'Valor VP800, a black terminal with a second, customer-facing screen on top, showing an inventory list.'],
    ['valor-vp800-2-payment.webp', 'payment screen', 'Valor VP800 showing a card payment in progress.'],
  ],
  'valor-vl550' => [
    ['valor-vl550-1-front.webp', 'front view', 'Valor VL550, a white Android terminal with a printer on top, showing a sale screen.'],
    ['valor-vl550-2-signature.webp', 'signature screen', 'Valor VL550 showing a tip and signature screen.'],
  ],
  'pax-a80' => [
    ['pax-a80-1-angled.webp', 'angled view', 'PAX A80, a white countertop terminal with a touchscreen and colored keypad, at an angle.'],
    ['pax-a80-2-portrait.webp', 'payment screen', 'PAX A80 showing an amount to pay.'],
  ],
  'pax-a35' => [
    ['pax-a35-1-angled.webp', 'angled view', 'PAX A35, a black PIN pad with a touchscreen above a keypad, at an angle.'],
    ['pax-a35-2-screen.webp', 'screen on', 'PAX A35 with its screen on.'],
  ],
  'fiserv-fd150' => [
    ['fiserv-fd150-1-with-pin-pad.webp', 'with RP10 PIN pad', 'Fiserv FD150 countertop terminal with a built-in printer, next to the optional RP10 PIN pad.'],
  ],
  'clover-flex-pocket' => [
    ['clover-flex-pocket-1-front.webp', 'front view', 'Clover Flex Pocket, a slim white handheld showing a payment screen.'],
  ],
];

$file_system = \Drupal::service('file_system');
$directory = 'public://devices';
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
$media_storage = \Drupal::entityTypeManager()->getStorage('media');

$attached = 0;
$placeholders = [];
foreach ($photos as $slug => $views) {
  $node = ib_node_by_alias('/hardware/' . $slug);
  if (!$node) {
    print "Missing device: $slug. Run 30-devices.php first.\n";
    continue;
  }

  // A gallery is replaced only when it is empty or holds placeholders only.
  $current = $node->get('field_gallery')->referencedEntities();
  $only_placeholders = $current && !array_filter($current, fn($media) => !str_ends_with($media->label(), '(placeholder)'));
  if ($current && !$only_placeholders) {
    continue;
  }

  $gallery = [];
  foreach ($views as [$filename, $view, $alt]) {
    $name = $node->label() . ': ' . $view;
    $existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
    if (!($item = reset($existing))) {
      $path = __DIR__ . '/assets/devices/' . $filename;
      [$width, $height] = getimagesize($path);
      $uri = $file_system->saveData(file_get_contents($path), $directory . '/' . $filename, FileExists::Replace);
      $file = File::create(['uri' => $uri, 'status' => 1, 'uid' => 1]);
      $file->save();
      $item = Media::create([
        'bundle' => 'image',
        'name' => $name,
        'uid' => 1,
        'status' => 1,
        'field_media_image' => ['target_id' => $file->id(), 'alt' => $alt, 'width' => $width, 'height' => $height],
      ]);
      $item->save();
    }
    $gallery[] = ['target_id' => $item->id()];
  }
  $placeholders = array_merge($placeholders, $current);
  $node->set('field_gallery', $gallery);
  $node->save();
  $attached++;
}

// Delete replaced placeholder drawings that nothing else uses.
$removed = 0;
foreach ($placeholders as $media) {
  $in_use = \Drupal::entityQuery('node')->accessCheck(FALSE)->condition('field_gallery', $media->id())->count()->execute();
  if (!$in_use) {
    $file = File::load($media->getSource()->getSourceFieldValue($media));
    $media->delete();
    $file?->delete();
    $removed++;
  }
}

print "Device photos attached to $attached devices. $removed placeholder drawings removed.\n";
