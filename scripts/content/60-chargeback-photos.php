<?php

/**
 * @file
 * Photos for the Chargeback protection page.
 *
 * Creates one image media item per photo in scripts/content/assets/chargeback-protection
 * and attaches each to its section. The photos are generated illustrations;
 * see the README beside them.
 *
 * Safe to re-run: a photo that already exists is reused, and a section that
 * already has an image is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/60-chargeback-photos.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Key => [file, width, height, alt text].
$photos = [
  'hero' => ['hero.jpg', 1168, 880, 'A boutique owner checks her phone at the checkout counter.'],
  'evidence' => ['evidence.jpg', 1024, 688, 'An online shop owner photographs a packed order and its receipt at her laptop.'],
];

$file_system = \Drupal::service('file_system');
$directory = 'public://chargeback-protection';
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
$media_storage = \Drupal::entityTypeManager()->getStorage('media');

$media = [];
foreach ($photos as $key => [$filename, $width, $height, $alt]) {
  $name = 'Chargeback protection: ' . $key;
  $existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
  if ($item = reset($existing)) {
    $media[$key] = $item->id();
    continue;
  }
  $uri = $file_system->saveData(file_get_contents(__DIR__ . '/assets/chargeback-protection/' . $filename), $directory . '/' . $filename, FileExists::Replace);
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
  $media[$key] = $item->id();
}

// Which photo goes on which setup card, by the card's title.
$cards = [];

$page = ib_node_by_alias('/solutions/chargeback-protection');
$attached = 0;
if ($page) {
  $attach = function ($paragraph, string $key) use ($media, &$attached): void {
    if ($paragraph->hasField('field_media') && $paragraph->get('field_media')->isEmpty()) {
      $paragraph->set('field_media', ['target_id' => $media[$key]])->save();
      $attached++;
    }
  };
  foreach ($page->get('field_sections')->referencedEntities() as $section) {
    switch ($section->bundle()) {
      case 'hero':
        $attach($section, 'hero');
        break;

      case 'media_split':
        $attach($section, 'evidence');
        break;

      case 'feature_grid':
        foreach ($section->get('field_items')->referencedEntities() as $card) {
              $key = $cards[$card->get('field_title')->value] ?? NULL;
          if ($key && !$card->get('field_media_label')->isEmpty()) {
            $attach($card, $key);
          }
        }
        break;
    }
  }
  if ($attached) {
    // Saving the page clears its cached render.
    $page->save();
  }
}

print "Chargeback protection photos are in place. $attached attached.\n";
