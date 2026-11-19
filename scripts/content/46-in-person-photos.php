<?php

/**
 * @file
 * Photos for the In person page.
 *
 * Creates one image media item per photo in scripts/content/assets/in-person
 * and attaches each to its section. The photos are generated illustrations;
 * see the README beside them.
 *
 * Safe to re-run: a photo that already exists is reused, and a section that
 * already has an image is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/46-in-person-photos.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Key => [file, width, height, alt text].
$photos = [
  'hero' => ['hero.jpg', 1024, 688, 'A customer taps a card on a countertop terminal while a shop owner in an apron smiles behind the counter.'],
  'countertop' => ['countertop.jpg', 1344, 752, 'A countertop card terminal beside a cash drawer on a wooden shop counter.'],
  'pos' => ['pos.jpg', 1344, 752, 'A shop employee uses a touchscreen POS station with a receipt printer beside it.'],
  'handheld' => ['handheld.jpg', 1344, 752, 'A customer at a cafe table taps a phone on a handheld terminal held by a server.'],
  'reprogram' => ['reprogram.jpg', 1024, 688, 'A specialist connects a laptop to a countertop terminal while the shop owner looks on.'],
];

$file_system = \Drupal::service('file_system');
$directory = 'public://in-person';
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
$media_storage = \Drupal::entityTypeManager()->getStorage('media');

$media = [];
foreach ($photos as $key => [$filename, $width, $height, $alt]) {
  $name = 'In person: ' . $key;
  $existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
  if ($item = reset($existing)) {
    $media[$key] = $item->id();
    continue;
  }
  $uri = $file_system->saveData(file_get_contents(__DIR__ . '/assets/in-person/' . $filename), $directory . '/' . $filename, FileExists::Replace);
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
$cards = ['Countertop terminal' => 'countertop', 'Full POS system' => 'pos', 'Handheld' => 'handheld'];

$page = ib_node_by_alias('/solutions/in-person');
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
        $attach($section, 'reprogram');
        break;

      case 'feature_grid':
        foreach ($section->get('field_items')->referencedEntities() as $card) {
          $key = $cards[$card->get('field_title')->value] ?? NULL;
          if ($key) {
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

print "In person photos are in place. $attached attached.\n";
