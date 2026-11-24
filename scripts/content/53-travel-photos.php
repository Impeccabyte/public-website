<?php

/**
 * @file
 * Photos for the "Trips we plan" cards on the Travel page (2026-11-24).
 *
 * Creates one image media item per photo in scripts/content/assets/travel
 * and attaches each to its card. The photos are generated illustrations;
 * see the README beside them.
 *
 * Safe to re-run: a photo that already exists is reused, and a card that
 * already has an image is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/53-travel-photos.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Card title => [file, width, height, alt text].
$photos = [
  'Business travel' => ['business.jpg', 1344, 752, 'A hotel room in morning light, with a work bag and blazer on an armchair and coffee and a croissant by the window.'],
  'Family trips' => ['family.jpg', 1344, 752, 'A family shares pancakes and juice at a table by a hotel window.'],
  'Celebrations' => ['celebrations.jpg', 1344, 752, 'A couple walks hand in hand down a cobblestone old-town street at golden hour.'],
];

$file_system = \Drupal::service('file_system');
$directory = 'public://travel';
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
$media_storage = \Drupal::entityTypeManager()->getStorage('media');

$page = ib_node_by_alias('/travel');
if (!$page) {
  print "No Travel page yet; run scripts/content/52-travel.php first.\n";
  return;
}

$attached = 0;
foreach ($page->get('field_sections')->referencedEntities() as $section) {
  if ($section->bundle() !== 'feature_grid' || !$section->hasField('field_items')) {
    continue;
  }
  foreach ($section->get('field_items')->referencedEntities() as $card) {
    $title = $card->get('field_title')->value;
    if (!isset($photos[$title]) || !$card->hasField('field_media') || !$card->get('field_media')->isEmpty()) {
      continue;
    }
    [$filename, $width, $height, $alt] = $photos[$title];
    $name = 'Travel: ' . $title;
    $existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
    if (!($media = reset($existing))) {
      $uri = $file_system->saveData(file_get_contents(__DIR__ . '/assets/travel/' . $filename), $directory . '/' . $filename, FileExists::Replace);
      $file = File::create(['uri' => $uri, 'status' => 1, 'uid' => 1]);
      $file->save();
      $media = Media::create([
        'bundle' => 'image',
        'name' => $name,
        'uid' => 1,
        'status' => 1,
        'field_media_image' => ['target_id' => $file->id(), 'alt' => $alt, 'width' => $width, 'height' => $height],
      ]);
      $media->save();
    }
    $card->set('field_media', ['target_id' => $media->id()])->save();
    $attached++;
  }
}
if ($attached) {
  $page->save();
}

print "Travel photos are in place. $attached attached.\n";
