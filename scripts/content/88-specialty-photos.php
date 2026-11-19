<?php

/**
 * @file
 * Photos for the twelve specialty vertical pages.
 *
 * One hero photo per vertical, plus one shared photo for each page's "Keep
 * your account in good standing" section. All are generated illustrations
 * kept neutral and PG on purpose; see scripts/content/assets/specialty.
 *
 * Safe to re-run: photos are reused if they exist, and a section that
 * already has an image is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/88-specialty-photos.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Slug => alt text. Files are scripts/content/assets/specialty/<slug>.jpg.
$photos = [
  'hemp-and-cbd' => 'A wellness shop owner talks with a customer at the counter.',
  'smoke-and-vape' => 'A shop owner rings up a purchase for a customer at the counter.',
  'kratom' => 'A botanical tea shop owner weighs an order behind the counter.',
  'firearms' => 'An outdoor goods store owner hands a customer a bag at the counter.',
  'adult-content' => 'An independent creator reviews her business accounts on a laptop in her home studio.',
  'dating' => 'Two app founders look at a phone together in their office.',
  'gaming' => 'Two colleagues talk at a desk in a small games company office.',
  'collections' => 'An account representative on a call takes notes at her desk.',
  'credit-repair' => 'An advisor and a client review a document together.',
  'lending' => 'A loan officer hands a client a folder across the desk.',
  'nutraceuticals-and-subscriptions' => 'A wellness brand team packs orders in a small warehouse.',
  'online-pharmacy-and-telehealth' => 'A clinician holds a video consultation from a calm home office.',
];
$shared = ['standing.jpg', 'A business owner reviews her merchant account dashboard at her desk in the evening.'];

$file_system = \Drupal::service('file_system');
$directory = 'public://specialty';
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
$media_storage = \Drupal::entityTypeManager()->getStorage('media');

$media_for = function (string $name, string $filename, string $alt) use ($file_system, $directory, $media_storage): int {
  $existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
  if ($item = reset($existing)) {
    return (int) $item->id();
  }
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
  return (int) $item->id();
};

$standing = $media_for('Specialty: good standing', $shared[0], $shared[1]);
$attached = 0;
foreach ($photos as $slug => $alt) {
  $page = ib_node_by_alias('/industries/specialty/' . $slug);
  if (!$page) {
    continue;
  }
  $hero_media = $media_for('Specialty: ' . $slug, $slug . '.jpg', $alt);
  $changed = FALSE;
  foreach ($page->get('field_sections')->referencedEntities() as $section) {
    $target = match ($section->bundle()) {
      'hero' => $hero_media,
      'media_split' => $standing,
      default => NULL,
    };
    if ($target && $section->hasField('field_media') && $section->get('field_media')->isEmpty()) {
      $section->set('field_media', ['target_id' => $target])->save();
      $attached++;
      $changed = TRUE;
    }
  }
  if ($changed) {
    $page->save();
  }
}

print "Specialty photos are in place. $attached attached.\n";
