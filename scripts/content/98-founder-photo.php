<?php

/**
 * @file
 * The photo beside the founder story on About us: Ryan Bealey's moms and
 * their dog, Olivia (Livvy), supplied 2026-11-23 in place of a portrait of
 * the founder. See scripts/content/assets/about/README.md.
 *
 * Safe to re-run: the photo is created once, and the story section only gets
 * it while it has no image. When the file in assets/about or the alt text
 * changes, the existing photo is replaced in place. A new photo gets a new
 * file name: images are cached for a year, so reusing a name would leave
 * browsers showing the old photo. Run it with
 *   ./scripts/drush php:script scripts/content/98-founder-photo.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

$name = 'About: founder';
$filename = 'founder-family.jpg';
$alt = "The founder's moms on a sunny boat ride with their dog, Olivia.";

$media_storage = \Drupal::entityTypeManager()->getStorage('media');
$existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
if (!($item = reset($existing))) {
  $file_system = \Drupal::service('file_system');
  $directory = 'public://about';
  $file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
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
}
else {
  // Swap in a newer photo from assets/about, keeping the same media item.
  $source = __DIR__ . '/assets/about/' . $filename;
  $image = $item->get('field_media_image')->first();
  $file = $image?->entity;
  $target = 'public://about/' . $filename;
  if ($file && ($file->getFileUri() !== $target || md5_file($source) !== md5_file($file->getFileUri()) || $image->alt !== $alt)) {
    [$width, $height] = getimagesize($source);
    $file_system = \Drupal::service('file_system');
    $old_uri = $file->getFileUri();
    foreach (\Drupal::entityTypeManager()->getStorage('image_style')->loadMultiple() as $style) {
      $style->flush($old_uri);
    }
    $file_system->saveData(file_get_contents($source), $target, FileExists::Replace);
    if ($old_uri !== $target) {
      $file_system->delete($old_uri);
      $file->setFileUri($target);
      $file->setFilename($filename);
    }
    $file->setSize(filesize($source));
    $file->save();
    $item->set('field_media_image', ['target_id' => $file->id(), 'alt' => $alt, 'width' => $width, 'height' => $height])->save();
    print "Founder photo: replaced with the new file.\n";
  }
}

$attached = 0;
$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
foreach ($storage->loadByProperties(['type' => 'media_split', 'field_heading' => 'Why we started Impeccabyte']) as $section) {
  if ($section->get('field_media')->isEmpty()) {
    $section->set('field_media', ['target_id' => $item->id()])->save();
    $attached++;
  }
}
if ($attached) {
  \Drupal::service('cache_tags.invalidator')->invalidateTags(['rendered']);
}
print "Founder photo: attached to $attached section.\n";
