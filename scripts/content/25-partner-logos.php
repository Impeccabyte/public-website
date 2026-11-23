<?php

/**
 * @file
 * Partner logos for the Home logo strip: the hardware brands, then the
 * gateways Maverick supports directly (docs/maverick-offerings.md, section 5),
 * added 2026-11-23. With that many logos the strip scrolls slowly, with a
 * pause button.
 *
 * Creates one image media item per brand from scripts/content/assets/
 * partner-logos and attaches them to Home's logo strip. The strip tints every
 * logo to one ink tone, so the files' own colors never show.
 *
 * Safe to re-run: a logo that already exists is left alone, and a strip that
 * already has logos is not changed. Run it with
 *   ./scripts/drush php:script scripts/content/25-partner-logos.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Brand => [file, width, height]. Sizes are the artwork's own proportions.
$logos = [
  'Clover' => ['clover.png', 240, 58],
  'PAX' => ['pax.svg', 87, 45],
  'Dejavoo' => ['dejavoo.svg', 85, 33],
  'Valor' => ['valor.svg', 160, 53],
  'Ingenico' => ['ingenico.svg', 111, 33],
];
// Gateways Maverick lists as core. USAePay is owned by NMI and Datacap only
// serves one POS, so neither has its own logo.
$gateways = [
  'Authorize.Net' => ['authorize-net.png', 206, 45],
  'NMI' => ['nmi.png', 838, 270],
  'PayTrace' => ['paytrace.svg', 201, 43],
  'Slim CD' => ['slimcd.png', 293, 46],
  'SwipeSimple' => ['swipesimple.svg', 197, 29],
  'iPOSPays' => ['ipospays.png', 600, 93],
];

$file_system = \Drupal::service('file_system');
$directory = 'public://partner-logos';
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
$media_storage = \Drupal::entityTypeManager()->getStorage('media');

$items = [];
$gateway_ids = [];
foreach ($logos + $gateways as $brand => [$filename, $width, $height]) {
  $name = 'Partner logo: ' . $brand;
  $existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
  if ($media = reset($existing)) {
    $items[] = ['target_id' => $media->id()];
    if (isset($gateways[$brand])) {
      $gateway_ids[] = $media->id();
    }
    continue;
  }
  $uri = $file_system->saveData(file_get_contents(__DIR__ . '/assets/partner-logos/' . $filename), $directory . '/' . $filename, FileExists::Replace);
  $file = File::create(['uri' => $uri, 'status' => 1, 'uid' => 1]);
  $file->save();
  $media = Media::create([
    'bundle' => 'image',
    'name' => $name,
    'uid' => 1,
    'status' => 1,
    'field_media_image' => ['target_id' => $file->id(), 'alt' => $brand, 'width' => $width, 'height' => $height],
  ]);
  $media->save();
  $items[] = ['target_id' => $media->id()];
  if (isset($gateways[$brand])) {
    $gateway_ids[] = $media->id();
  }
}

// Home's logo strip. The Maverick line belongs to the footer disclosure, so
// the strip's intro only introduces the brands.
$home = ib_node_by_alias('/home');
if ($home) {
  foreach ($home->get('field_sections')->referencedEntities() as $section) {
    if ($section->bundle() !== 'logo_strip') {
      continue;
    }
    $changed = FALSE;
    if ($section->get('field_logos')->isEmpty()) {
      $section->set('field_logos', $items);
      $changed = TRUE;
    }
    // The gateways joined the strip on 2026-11-23. A strip built before then
    // gets them once, after its own logos; a strip that already shows any of
    // them is left as it is.
    $current = array_column($section->get('field_logos')->getValue(), 'target_id');
    if (!array_intersect($current, $gateway_ids)) {
      $section->set('field_logos', array_merge($section->get('field_logos')->getValue(), array_map(fn($id) => ['target_id' => $id], $gateway_ids)));
      $changed = TRUE;
    }
    if ($section->get('field_intro')->value === 'Authorized agent of Maverick Payments. Hardware and partners we set up:') {
      $section->set('field_intro', 'Hardware and partners we set up:');
      $changed = TRUE;
    }
    if ($changed) {
      $section->save();
      $home->save();
    }
  }
}

print count($items) . " partner logos are in place.\n";
