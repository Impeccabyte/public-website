<?php

/**
 * @file
 * Formal names, 2026-11-21. Industries ("Restaurants & Bars", "Retail",
 * "Auto Repair") everywhere an industry is named as such; Solutions, Hardware
 * and Company in the menus, on page titles and on their category terms.
 *
 * The other scripts already use the formal names, so a fresh install needs
 * nothing from this one. It renames what a site built before this date
 * holds: industry terms, industry page titles, menu links, paragraph labels
 * (eyebrows, chips, cards, link text, "<Name> questions" headings and the
 * specialty hero headings) and the labels in the two lead forms. A name used
 * in the middle of a sentence ("for auto repair shops") stays as it is.
 *
 * Safe to re-run: only values still holding an old name change. Run it with
 *   ./scripts/drush php:script scripts/content/99-formal-names.php
 * then reindex search and export config.
 */

use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;
use Drupal\webform\Entity\Webform;
use Drupal\Core\Serialization\Yaml;

require_once dirname(__DIR__) . '/setup/_helpers.php';

$names = [
  'Restaurants and bars' => 'Restaurants & Bars',
  'Food trucks' => 'Food Trucks',
  'Retail shops' => 'Retail',
  'Salons and spas' => 'Salons & Spas',
  'Home services' => 'Home Services',
  'Healthcare and dental' => 'Healthcare & Dental',
  'Auto repair' => 'Auto Repair',
  'Professional services' => 'Professional Services',
  'Pop-ups and markets' => 'Pop-Ups & Markets',
  'Specialty industries' => 'Specialty Industries',
  'Hemp and CBD' => 'Hemp & CBD',
  'Smoke and vape' => 'Smoke & Vape',
  'Adult content' => 'Adult Content',
  'Credit repair' => 'Credit Repair',
  'Nutraceuticals and subscriptions' => 'Nutraceuticals & Subscriptions',
  'Online pharmacy and telehealth' => 'Online Pharmacy & Telehealth',
];

// A whole value that is a name, or a heading that starts with one.
$rename = function (?string $value) use ($names): ?string {
  if ($value === NULL || $value === '') {
    return NULL;
  }
  if (isset($names[$value])) {
    return $names[$value];
  }
  foreach ($names as $old => $new) {
    foreach ([' questions', ' merchant accounts, explained plainly.'] as $suffix) {
      if ($value === $old . $suffix) {
        return $new . $suffix;
      }
    }
  }
  return NULL;
};

// Solutions, Hardware and Company, 2026-11-21: formal names in the menus,
// on page titles and on the category terms only. Text inside pages keeps
// the plain terms ("dual pricing" as a way to price, "countertop terminal"
// as a kind of device).
$labels = [
  'Accept payments' => 'Accept Payments',
  'Lower your costs' => 'Lower Your Costs',
  'Run your business' => 'Run Your Business',
  'In person' => 'In Person',
  'On the go' => 'On the Go',
  'Recurring billing' => 'Recurring Billing',
  'Dual pricing' => 'Dual Pricing',
  'Interchange plus' => 'Interchange Plus',
  'Chargeback protection' => 'Chargeback Protection',
  'In-person payments' => 'In-Person Payments',
  'Online payments' => 'Online Payments',
  'Mobile payments' => 'Mobile Payments',
  'All devices' => 'All Devices',
  'Countertop terminals' => 'Countertop Terminals',
  'Handheld terminals' => 'Handheld Terminals',
  'POS systems' => 'POS Systems',
  'Mobile readers' => 'Mobile Readers',
  'Self-order kiosks' => 'Self-Order Kiosks',
  'About us' => 'About Us',
  'Merchant log in' => 'Merchant Log In',
];
$label_vocabularies = [
  'device_type' => [
    'Countertop terminal' => 'Countertop Terminal',
    'Mobile reader' => 'Mobile Reader',
    'POS system' => 'POS System',
    'Self-order kiosk' => 'Self-Order Kiosk',
  ],
  'solution_category' => array_intersect_key($labels, array_flip(['Accept payments', 'Lower your costs', 'Run your business'])),
];
$label_menus = ['main', 'footer-solutions', 'footer-hardware', 'footer-company'];

$count = ['terms' => 0, 'pages' => 0, 'menu links' => 0, 'paragraphs' => 0, 'forms' => 0];

foreach ($label_vocabularies as $vid => $map) {
  foreach (Term::loadMultiple(\Drupal::entityQuery('taxonomy_term')->accessCheck(FALSE)->condition('vid', $vid)->execute()) as $term) {
    if (isset($map[$term->getName()])) {
      $term->setName($map[$term->getName()])->save();
      $count['terms']++;
    }
  }
}
foreach (Node::loadMultiple(\Drupal::entityQuery('node')->accessCheck(FALSE)->condition('type', ['solution', 'landing_page'], 'IN')->execute()) as $node) {
  if (isset($labels[$node->getTitle()])) {
    $node->setTitle($labels[$node->getTitle()])->save();
    $count['pages']++;
  }
}
foreach (MenuLinkContent::loadMultiple(\Drupal::entityQuery('menu_link_content')->accessCheck(FALSE)->condition('menu_name', $label_menus, 'IN')->execute()) as $link) {
  if (isset($labels[$link->getTitle()])) {
    $link->set('title', $labels[$link->getTitle()])->save();
    $count['menu links']++;
  }
}

foreach (Term::loadMultiple(\Drupal::entityQuery('taxonomy_term')->accessCheck(FALSE)->condition('vid', 'industry')->execute()) as $term) {
  if ($new = $rename($term->getName())) {
    $term->setName($new)->save();
    $count['terms']++;
  }
}

foreach (Node::loadMultiple(\Drupal::entityQuery('node')->accessCheck(FALSE)->condition('type', 'industry_page')->execute()) as $node) {
  if ($new = $rename($node->getTitle())) {
    $node->setTitle($new)->save();
    $count['pages']++;
  }
}

foreach (MenuLinkContent::loadMultiple(\Drupal::entityQuery('menu_link_content')->accessCheck(FALSE)->execute()) as $link) {
  if ($new = $rename($link->getTitle())) {
    $link->set('title', $new)->save();
    $count['menu links']++;
  }
}

$ids = \Drupal::entityQuery('paragraph')->accessCheck(FALSE)->execute();
foreach (array_chunk($ids, 200) as $chunk) {
  foreach (Paragraph::loadMultiple($chunk) as $paragraph) {
    $changed = FALSE;
    foreach ($paragraph->getFieldDefinitions() as $field => $definition) {
      if (!str_starts_with($field, 'field_')) {
        continue;
      }
      $type = $definition->getType();
      if (in_array($type, ['string', 'string_long'], TRUE)) {
        $values = $paragraph->get($field)->getValue();
        foreach ($values as $delta => $item) {
          if ($new = $rename($item['value'] ?? NULL)) {
            $values[$delta]['value'] = $new;
            $changed = TRUE;
          }
        }
        $changed && $paragraph->set($field, $values);
      }
      elseif ($type === 'link' && !$paragraph->get($field)->isEmpty()) {
        $item = $paragraph->get($field)->first();
        if ($new = $rename($item->title)) {
          $item->title = $new;
          $changed = TRUE;
        }
      }
    }
    if ($changed) {
      $paragraph->save();
      $count['paragraphs']++;
    }
  }
}

// Lead forms. The quote form keeps its option keys; the specialty form's
// keys are the term names, which its page pre-selects by.
foreach (['get_a_quote' => 'business_type', 'specialty_prequal' => 'vertical'] as $id => $key) {
  $webform = Webform::load($id);
  if (!$webform) {
    continue;
  }
  $elements = Yaml::decode($webform->get('elements'));
  $element = &ib_webform_find_element($elements, $key);
  if (!$element || empty($element['#options'])) {
    unset($element);
    continue;
  }
  $options = [];
  foreach ($element['#options'] as $value => $label) {
    $formal = [
      'Restaurant or bar' => 'Restaurants & Bars',
      'Food truck' => 'Food Trucks',
      'Retail shop' => 'Retail',
      'Salon or spa' => 'Salons & Spas',
      'Home or field services' => 'Home Services',
      'Healthcare or dental' => 'Healthcare & Dental',
      'Nonprofit' => 'Nonprofits',
    ][$label] ?? $rename($label) ?? $label;
    if ($id === 'specialty_prequal') {
      $value = $formal;
    }
    $options[$value] = $formal;
  }
  if ($options !== $element['#options']) {
    $element['#options'] = $options;
    $webform->set('elements', Yaml::encode($elements))->save();
    $count['forms']++;
  }
  unset($element);
}

function &ib_webform_find_element(array &$elements, string $key) {
  $none = NULL;
  foreach ($elements as $name => &$element) {
    if ($name === $key) {
      return $element;
    }
    if (is_array($element)) {
      $found = &ib_webform_find_element($element, $key);
      if ($found !== NULL) {
        return $found;
      }
    }
  }
  return $none;
}

$summary = [];
foreach ($count as $what => $n) {
  $summary[] = "$n $what";
}
print 'Formal names: renamed ' . implode(', ', $summary) . ".\n";
