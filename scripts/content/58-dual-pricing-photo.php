<?php

/**
 * @file
 * The photo for the Dual pricing page's "What your customers see" section.
 *
 * A generated illustration; see scripts/content/assets/dual-pricing.
 *
 * Safe to re-run: the photo is reused if it exists, and a section that
 * already has an image is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/58-dual-pricing-photo.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

$name = 'Dual pricing: counter';
$filename = 'counter.jpg';
$alt = 'A customer taps a card on the terminal at a coffee shop counter, beside a small price sign.';

$media_storage = \Drupal::entityTypeManager()->getStorage('media');
$existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
if (!($item = reset($existing))) {
  $file_system = \Drupal::service('file_system');
  $directory = 'public://dual-pricing';
  $file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
  $source = __DIR__ . '/assets/dual-pricing/' . $filename;
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
if ($page = ib_node_by_alias('/solutions/dual-pricing')) {
  foreach ($page->get('field_sections')->referencedEntities() as $section) {
    if ($section->bundle() === 'media_split' && $section->get('field_media')->isEmpty()) {
      $section->set('field_media', ['target_id' => $item->id()])->save();
      $attached++;
    }
  }
  if ($attached) {
    $page->save();
  }
}

print "Dual pricing photo is in place. $attached attached.\n";
