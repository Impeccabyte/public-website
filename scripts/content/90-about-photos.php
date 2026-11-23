<?php

/**
 * @file
 * Photos for the About us page.
 *
 * Attaches the hero photo and the "Reach us" street photo from
 * scripts/content/assets/about. The founder photo in "Our story" is never
 * generated; that section keeps its placeholder until a real photo arrives.
 *
 * Safe to re-run: a photo that already exists is reused, and a section that
 * already has an image is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/90-about-photos.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Key => [file, alt text].
$photos = [
  'hero' => ['hero.jpg', 'A payments specialist points out a feature on a countertop terminal to a cafe owner.'],
  'street' => ['street.jpg', 'A row of local shops under a live oak on a sunny Austin street.'],
];

$file_system = \Drupal::service('file_system');
$directory = 'public://about';
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
$media_storage = \Drupal::entityTypeManager()->getStorage('media');

$media = [];
foreach ($photos as $key => [$filename, $alt]) {
  $name = 'About: ' . $key;
  $existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
  if ($item = reset($existing)) {
    $media[$key] = $item->id();
    continue;
  }
  $source = __DIR__ . '/assets/about/' . $filename;
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
  $media[$key] = $item->id();
}

$page = ib_node_by_alias('/about');
$attached = 0;
if ($page) {
  foreach ($page->get('field_sections')->referencedEntities() as $section) {
    $key = match (TRUE) {
      $section->bundle() === 'hero' => 'hero',
      $section->bundle() === 'media_split' && $section->get('field_heading')->value === 'A real person, close by' => 'street',
      default => NULL,
    };
    if ($key && $section->get('field_media')->isEmpty()) {
      $section->set('field_media', ['target_id' => $media[$key]])->save();
      $attached++;
    }
  }
  if ($attached) {
    $page->save();
  }
}

print "About us photos are in place. $attached attached.\n";
