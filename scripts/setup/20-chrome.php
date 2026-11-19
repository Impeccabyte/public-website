<?php

/**
 * @file
 * Phase 2, global chrome: contact settings, menus, the menu link icon field,
 * the mega menu's featured card block type, the newsletter form and the
 * theme's block layout.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/20-chrome.php
 * then export config.
 */

use Drupal\block\Entity\Block;
use Drupal\block_content\Entity\BlockContentType;
use Drupal\webform\Entity\Webform;

require_once __DIR__ . '/_helpers.php';

// Site settings added after the module was first installed.
$settings = \Drupal::configFactory()->getEditable('impeccabyte_site.settings');
$defaults = [
  'phone' => '(512) [XXX-XXXX]',
  'hours' => 'Mon to Fri, 8 to 6 Central',
  'email' => 'hello@impeccabyte.com',
  'address_line1' => '[Street address], Suite [XXX]',
  'address_line2' => 'Austin, TX 787[XX]',
  'login_url' => 'https://app.impeccabyte.com',
  'disclosure' => 'Merchant accounts are provided by Maverick Payments and its sponsor banks. Impeccabyte is an authorized sales agent of Maverick Payments.',
];
foreach ($defaults as $key => $value) {
  if ($settings->get($key) === NULL) {
    $settings->set($key, $value);
  }
}
$settings->save();

// Menus. The main menu ships with core; the footer has one menu per column.
ib_menu('footer-solutions', 'Solutions', 'Footer column: solutions.');
ib_menu('footer-hardware', 'Hardware', 'Footer column: hardware.');
ib_menu('footer-industries', 'Industries', 'Footer column: industries.');
ib_menu('footer-company', 'Company', 'Footer column: company.');
ib_menu('footer-legal', 'Legal links', 'The row of links under the footer disclosure.');

// The standard profile's Home link is not part of this site's navigation.
$menu_links = \Drupal::service('plugin.manager.menu.link');
if ($menu_links->hasDefinition('standard.front_page') && $menu_links->getDefinition('standard.front_page')['enabled']) {
  $menu_links->updateDefinition('standard.front_page', ['enabled' => 0]);
}

// Main menu links show a Lucide icon and their description in the mega menu.
ib_field('menu_link_content', 'menu_link_content', 'field_icon', 'string', 'Icon', [
  'description' => 'A Lucide icon name, such as store or globe. Shown beside the link in the mega menu.',
  'weight' => 5,
  'hide' => TRUE,
]);

// The mega menu's featured card.
if (!BlockContentType::load('featured_card')) {
  BlockContentType::create(['id' => 'featured_card', 'label' => 'Featured card', 'description' => 'The highlighted card in the mega menu: badge, title, body and one link.'])->save();
}
ib_field('block_content', 'featured_card', 'field_badge', 'string', 'Badge', ['weight' => 1]);
ib_field('block_content', 'featured_card', 'field_heading', 'string', 'Title', ['required' => TRUE, 'weight' => 2]);
ib_field('block_content', 'featured_card', 'field_body', 'string_long', 'Body', ['weight' => 3]);
ib_field('block_content', 'featured_card', 'field_link', 'link', 'Link', ['settings' => ['link_type' => 17, 'title' => 2], 'weight' => 4]);

// Newsletter signup, shown in the footer.
if (!Webform::load('newsletter')) {
  $elements = <<<YAML
email:
  '#type': email
  '#title': 'Email address'
  '#placeholder': you@yourbusiness.com
  '#required': true
  '#attributes':
    autocomplete: email
actions:
  '#type': webform_actions
  '#title': 'Submit button'
  '#submit__label': Subscribe
  '#submit__attributes':
    class:
      - button--accent
      - button--lg
YAML;
  $webform = Webform::create(['id' => 'newsletter', 'title' => 'Newsletter', 'description' => 'Email signup shown in the footer.', 'category' => 'Leads', 'elements' => $elements]);
  $webform->setSettings([
    'ajax' => TRUE,
    'ajax_scroll_top' => '',
    'page' => FALSE,
    'confirmation_type' => 'inline',
    'confirmation_message' => 'Thanks. You are on the list.',
    'form_novalidate' => FALSE,
  ]);
  $webform->save();
}

// Block layout. The header and footer are components in page.html.twig, so
// the blocks the theme inherited at install are removed.
$keep = ['impeccabyte_content', 'impeccabyte_messages', 'impeccabyte_primary_local_tasks', 'impeccabyte_secondary_local_tasks', 'impeccabyte_primary_admin_actions', 'impeccabyte_featured_card'];
foreach (\Drupal::entityTypeManager()->getStorage('block')->loadByProperties(['theme' => 'impeccabyte']) as $block) {
  if (!in_array($block->id(), $keep, TRUE)) {
    $block->delete();
  }
}
$place = function (string $id, string $plugin, string $region, int $weight, array $settings = []): void {
  $block = Block::load($id);
  if (!$block) {
    Block::create(['id' => $id, 'theme' => 'impeccabyte', 'plugin' => $plugin, 'region' => $region, 'weight' => $weight, 'settings' => $settings + ['label_display' => '0']])->save();
  }
  elseif ($block->getRegion() !== $region) {
    $block->setRegion($region)->save();
  }
};
$place('impeccabyte_messages', 'system_messages_block', 'highlighted', 0);
$place('impeccabyte_primary_local_tasks', 'local_tasks_block', 'highlighted', 1, ['label' => 'Primary tabs', 'primary' => TRUE, 'secondary' => FALSE]);
$place('impeccabyte_secondary_local_tasks', 'local_tasks_block', 'highlighted', 2, ['label' => 'Secondary tabs', 'primary' => FALSE, 'secondary' => TRUE]);
$place('impeccabyte_primary_admin_actions', 'local_actions_block', 'highlighted', 3, ['label' => 'Primary admin actions']);
$place('impeccabyte_content', 'system_main_block', 'content', 0, ['label' => 'Main page content']);
// The featured card's content is created by scripts/content/10-chrome.php
// with this UUID.
$place('impeccabyte_featured_card', 'block_content:b1f0a8e2-6a53-4c0e-9d7a-5e1c3a7f2d41', 'mega_featured', 0, ['label' => 'Mega menu featured card', 'status' => TRUE, 'info' => '', 'view_mode' => 'full']);

// The theme draws its own logo, so the default logo file is not requested.
\Drupal::configFactory()->getEditable('impeccabyte.settings')->set('logo.use_default', FALSE)->set('favicon.use_default', TRUE)->save();

print "Global chrome is in place.\n";
