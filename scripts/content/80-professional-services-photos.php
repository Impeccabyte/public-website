<?php

/**
 * @file
 * Photos for the Professional services page.
 *
 * Creates one image media item per photo in scripts/content/assets/professional-services
 * and attaches each to its section. The photos are generated illustrations;
 * see the README beside them.
 *
 * Safe to re-run: a photo that already exists is reused, and a section that
 * already has an image is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/80-professional-services-photos.php
 */

use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

require_once dirname(__DIR__) . '/setup/_helpers.php';

// Key => [file, width, height, alt text].
$photos = [
  'hero' => ['hero.jpg', 1168, 880, 'A consultant finishes sending an invoice from her studio office.'],
  'invoice' => ['invoice.jpg', 1344, 752, 'A client pays an invoice on a laptop at a home office desk.'],
  'phone' => ['phone.jpg', 1344, 752, 'A bookkeeper takes a payment over the phone at her desk.'],
  'retainer' => ['retainer.jpg', 1344, 752, 'A consultant meets a client at a small round table.'],
  'partners' => ['partners.jpg', 1024, 688, 'Two partners review billing together at a small design firm.'],
];

$file_system = \Drupal::service('file_system');
$directory = 'public://professional-services';
$file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
$media_storage = \Drupal::entityTypeManager()->getStorage('media');

$media = [];
foreach ($photos as $key => [$filename, $width, $height, $alt]) {
  $name = 'Professional services: ' . $key;
  $existing = $media_storage->loadByProperties(['bundle' => 'image', 'name' => $name]);
  if ($item = reset($existing)) {
    $media[$key] = $item->id();
    continue;
  }
  $uri = $file_system->saveData(file_get_contents(__DIR__ . '/assets/professional-services/' . $filename), $directory . '/' . $filename, FileExists::Replace);
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
$cards = ['Invoices' => 'invoice', 'Over the phone' => 'phone', 'Retainers' => 'retainer'];

$page = ib_node_by_alias('/industries/professional-services');
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
        $attach($section, 'partners');
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

print "Professional services photos are in place. $attached attached.\n";
