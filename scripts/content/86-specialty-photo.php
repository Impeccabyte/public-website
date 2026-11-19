<?php

/**
 * @file
 * The photo for the Specialty industries hub's hero. Neutral on purpose: people and paperwork, never products.
 *
 * A generated illustration; see scripts/content/assets/specialty.
 *
 * Safe to re-run: the photo is reused if it exists, and a section that
 * already has an image is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/86-specialty-photo.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

$name = 'Specialty: hub';
$filename = 'hub.jpg';
$alt = 'A business owner and a local specialist go over paperwork at a table.';

$media_storage = \Drupal::entityTypeManager()->getStorage('media');
$existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
if (!($item = reset($existing))) {
  $file_system = \Drupal::service('file_system');
  $directory = 'public://specialty';
  $file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
  $source = __DIR__ . '/assets/specialty/' . $filename;
  [$width, $height] = getimagesize($source);
  $uri = $file_system->saveData(file_get_contents($source), $directory . '/' . $filename, FileExists::Replace);
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

$attached = 0;
if ($page = ib_node_by_alias('/industries/specialty')) {
  foreach ($page->get('field_sections')->referencedEntities() as $section) {
    if ($section->bundle() === 'hero' && $section->get('field_media')->isEmpty()) {
      $section->set('field_media', ['target_id' => $item->id()])->save();
      $attached++;
    }
  }
  if ($attached) {
    $page->save();
  }
}

print "Specialty hub photo is in place. $attached attached.\n";
