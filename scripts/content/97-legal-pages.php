<?php

/**
 * @file
 * Terms and Privacy from GetTerms, and no Accessibility page (2026-11-23).
 *
 * - /privacy and /terms: the placeholder callout becomes a Legal document
 *   section, which shows the policy through GetTerms' embed with a loading
 *   skeleton. Only a page that still has its original hero and placeholder
 *   callout is changed, so a page edited in the admin is left alone.
 * - /accessibility is unpublished (not deleted).
 * - The Accessibility and Sitemap links in the footer are switched off.
 * - The Privacy search description no longer restates the old placeholder.
 *
 * On a fresh install the source scripts already do all of this and this
 * script changes nothing. Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/97-legal-pages.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$changes = [];

// Placeholder callouts become the GetTerms embed.
$documents = [
  '/privacy' => ['privacy', '[Privacy policy to be supplied'],
  '/terms' => ['terms-of-service', '[Website terms of use to be supplied'],
];
foreach ($documents as $alias => [$document, $placeholder]) {
  $node = ib_node_by_alias($alias);
  if (!$node) {
    continue;
  }
  $sections = $node->get('field_sections')->referencedEntities();
  $bundles = array_map(fn($section) => $section->bundle(), $sections);
  if ($bundles !== ['hero', 'callout'] || !str_contains((string) $sections[1]->get('field_text')->value, $placeholder)) {
    continue;
  }
  $embed = ib_paragraph('legal_document', ['document' => $document, 'getterms_id' => '7RS3i']);
  $node->set('field_sections', [
    ['target_id' => $sections[0]->id(), 'target_revision_id' => $sections[0]->getRevisionId()],
    $embed,
  ])->save();
  $sections[1]->delete();
  $changes[] = "$alias shows the GetTerms policy";
}

// No Accessibility page.
$accessibility = ib_node_by_alias('/accessibility');
if ($accessibility && $accessibility->isPublished()) {
  $accessibility->setUnpublished()->save();
  $changes[] = '/accessibility unpublished';
}

// Footer: no Accessibility or Sitemap link.
$links = \Drupal::entityTypeManager()->getStorage('menu_link_content')->loadByProperties(['menu_name' => 'footer-legal']);
foreach ($links as $link) {
  if (in_array($link->get('link')->uri, ['internal:/accessibility', 'internal:/sitemap.xml'], TRUE) && $link->isEnabled()) {
    $link->set('enabled', FALSE)->save();
    $changes[] = 'footer link switched off: ' . $link->getTitle();
  }
}

// The Privacy search description.
$privacy = ib_node_by_alias('/privacy');
$old = 'How Impeccabyte handles the information you send us. We use your details only to answer your request, and we never sell them.';
if ($privacy && $privacy->hasField('field_meta_description') && $privacy->get('field_meta_description')->value === $old) {
  $privacy->set('field_meta_description', "Impeccabyte's privacy policy: what information this website collects and how we use and protect it.")->save();
  $changes[] = 'Privacy search description';
}

print $changes ? "Legal pages:\n- " . implode("\n- ", $changes) . "\n" : "Legal pages: nothing to change.\n";
