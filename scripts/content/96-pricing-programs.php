<?php

/**
 * @file
 * Merchant prices for the three pricing programs, from Impeccabyte's pricing
 * playbook (November 2026).
 *
 * Only merchant-facing prices are used: program rates and monthly fees, the
 * chargeback fee, the shared fee schedule and Clover's monthly device fees. Nothing about what
 * Impeccabyte pays or keeps goes into this site. Every price sits in a field
 * that shows "Pricing coming soon" while "Show prices" is off.
 *
 * The source scripts (20-pages.php, 40-solutions-industries.php,
 * 57-dual-pricing.php) already hold these values for a fresh install. This
 * script brings an existing site in line, and only replaces a value that
 * still holds the old placeholder or wording, so admin edits are kept. It
 * also adds Clover's monthly fees to Clover devices that have none.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/content/96-pricing-programs.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$storage = \Drupal::entityTypeManager()->getStorage('paragraph');
$changes = [];

// Set a field only while it still holds the old value.
$swap = function ($entity, string $field, $old, $new) {
  if ($entity->hasField($field) && (string) $entity->get($field)->value === (string) $old) {
    $entity->set($field, $new);
    return TRUE;
  }
  return FALSE;
};

// Pricing cards.
$cards = [
  'Flat rate' => [['[X.X]% + [XX]¢', '2.59% + 10¢'], ['per card sale', 'per in-person card sale']],
  'Dual pricing' => [['$19.95/mo', '$14.95/mo'], ['dual pricing software, plus gateway fees', 'program fee, gateway included']],
  'Interchange plus' => [['Cost + [0.XX]%', 'Cost + 0.35%'], ['+ [XX]¢ per card sale', '+ 10¢ per in-person card sale']],
];
foreach ($storage->loadByProperties(['type' => 'pricing_card']) as $card) {
  $title = $card->get('field_title')->value;
  if (isset($cards[$title])) {
    [[$old_value, $new_value], [$old_unit, $new_unit]] = $cards[$title];
    $changed = $swap($card, 'field_value', $old_value, $new_value);
    $changed = $swap($card, 'field_unit', $old_unit, $new_unit) || $changed;
    if ($changed) {
      $card->save();
      $changes[] = "pricing card: $title";
    }
  }
}
// Flat rate card bullet, shortened 2026-11-21.
foreach ($storage->loadByProperties(['type' => 'pricing_card', 'field_title' => 'Flat rate']) as $card) {
  $bullets = array_column($card->get('field_bullets')->getValue(), 'value');
  if (($i = array_search('Best for new and lower-volume businesses', $bullets)) !== FALSE) {
    $bullets[$i] = 'Best for new businesses';
    $card->set('field_bullets', $bullets)->save();
    $changes[] = 'pricing card: Flat rate bullet';
  }
}
foreach ($storage->loadByProperties(['type' => 'pricing_cards']) as $group) {
  if ($swap($group, 'field_price_note', 'Hardware from $10/mo leased or $199 to own. Gateway fees depend on your setup and are listed on every quote.', 'Hardware from $10/mo leased or $199 to own. Keyed and online rates, and any gateway your setup needs, are listed on every quote.')) {
    $group->save();
    $changes[] = 'pricing cards: price note';
  }
}

// Comparison table on Pricing: add price rows, fix the volume threshold and
// stop calling the dual pricing difference a fee. Only a table that still
// has the original six rows is changed.
$original = ['How it works', 'Who pays the fee', 'Monthly predictability', 'Usually cheapest when', 'Setup needed', 'Statement clarity'];
foreach ($storage->loadByProperties(['type' => 'comparison_table']) as $table) {
  if ((string) $table->get('field_columns')->value !== 'Flat rate' && !in_array('Flat rate', array_column($table->get('field_columns')->getValue(), 'value'))) {
    continue;
  }
  $rows = $table->get('field_items')->referencedEntities();
  $labels = array_map(fn($row) => $row->get('field_label')->value, $rows);
  if ($labels !== $original) {
    continue;
  }
  [$how, $who, $predictability, $cheapest, $setup, $clarity] = $rows;
  $who->set('field_label', 'Who covers the processing cost')->save();
  $predictability->set('field_cells', ['High. Same rate every time.', 'High. One fixed monthly fee.', 'Medium. Varies with card mix.'])->save();
  $cheapest->set('field_cells', ['You process under about $15,000 a month', 'Your customers are comfortable seeing two prices', 'You process $15,000 or more a month'])->save();
  $new = fn(string $label, array $cells, bool $price) => ib_paragraph('table_row', ['label' => $label, 'cells' => $cells, 'is_price' => (int) $price]);
  $ref = fn($row) => ['target_id' => $row->id(), 'target_revision_id' => $row->getRevisionId()];
  $table->set('field_items', [
    $ref($how),
    $new('Rate', ['2.59% + 10¢ in person. 3.29% + 30¢ keyed or online. American Express included.', 'The card price is the cash price plus 4%. Cash customers pay the posted cash price.', 'Card-network cost + 0.35% + 10¢ in person, or + 0.50% + 20¢ keyed or online. Lower markups from $75,000 a month.'], TRUE),
    $new('Monthly fee', ['$9.95 for your account and PCI compliance', '$14.95 program fee, with the gateway, dashboard and PCI compliance included', '$19.90 for your account, reporting and PCI compliance'], TRUE),
    $ref($who),
    $ref($predictability),
    $ref($cheapest),
    $ref($setup),
    $ref($clarity),
    $new('Chargeback fee', ['$25', '$25', '$25'], TRUE),
    $new('Account setup, batch, statement or annual fees', ['None', 'None', 'None'], TRUE),
  ]);
  $table->save();
  $changes[] = 'comparison table: rates, monthly fees, chargeback fee';
}

// The "no other fees" row: scoped to the processing account (gateways and
// the card updater have setup fees) and hidden with the other prices.
foreach ($storage->loadByProperties(['type' => 'table_row', 'field_label' => 'Batch, statement, annual or setup fees']) as $row) {
  $row->set('field_label', 'Account setup, batch, statement or annual fees')->set('field_is_price', 1)->save();
  $changes[] = 'comparison table: no-fees row scoped and hidden with prices';
}

// "Card updater included": the playbook prices the updater as an add-on.
foreach ($storage->loadByProperties(['type' => 'setup_line', 'field_note' => 'Card updater included']) as $line) {
  $line->set('field_note', 'Card account updater')->save();
  $changes[] = 'Home chip: card updater no longer "included"';
}
foreach ($storage->loadByProperties(['type' => 'hero']) as $hero) {
  $items = array_column($hero->get('field_checklist')->getValue(), 'value');
  if (($i = array_search('Card updater included', $items)) !== FALSE) {
    $items[$i] = 'Card account updater';
    $hero->set('field_checklist', $items)->save();
    $changes[] = 'Recurring billing hero: card updater no longer "included"';
  }
}

// "Other fees" table on Pricing, after the program comparison.
$pricing = ib_node_by_alias('/pricing');
if ($pricing) {
  $sections = $pricing->get('field_sections')->referencedEntities();
  $has_fees = (bool) array_filter($sections, fn($p) => $p->bundle() === 'comparison_table' && $p->get('field_eyebrow')->value === 'Other fees');
  $position = NULL;
  foreach ($sections as $i => $p) {
    if ($p->bundle() === 'comparison_table') {
      $position = $i;
    }
  }
  if (!$has_fees && $position !== NULL) {
    $fee = fn(string $label, string $value) => ['_type' => 'table_row', 'label' => $label, 'cells' => [$value], 'is_price' => 1];
    $table = ib_paragraph('comparison_table', [
      'eyebrow' => 'Other fees',
      'heading' => 'Every other fee, in one place',
      'intro' => 'These apply on any program, only when you use the service. Your quote lists the ones that fit your setup.',
      'columns' => ['What it costs'],
      'items' => [
        $fee('Chargeback', '$25 each'),
        $fee('Retrieval request', '$15 each'),
        $fee('Voice authorization', '95¢ each'),
        $fee('Address verification', '5¢ each on interchange plus. Included on flat rate and dual pricing.'),
        $fee('ACH (eCheck) payments', '35¢ each, plus $9.95/mo'),
        $fee('ACH returns', '$5 each, or $25 for an unauthorized return'),
        $fee('3D Secure for online payments', '30¢ each'),
      ],
      'footnote' => 'Clover devices add their own monthly fees, listed on each device page.',
    ]);
    $refs = array_map(fn($p) => ['target_id' => $p->id(), 'target_revision_id' => $p->getRevisionId()], $sections);
    array_splice($refs, $position + 1, 0, [$table]);
    $pricing->set('field_sections', $refs)->save();
    $changes[] = 'Pricing: "Other fees" table';
  }
}

// Gateway and software fees come off the "Other fees" table (2026-11-21);
// they are quoted per setup instead. The card account updater followed on
// 2026-11-23: it belongs to the Maverick gateway, not to every program.
$dropped = ['Card account updater', 'Online checkout gateway (Accept.Blue)', 'Virtual terminal gateway (NMI)', 'B2B gateway with Level 2 and 3 data (Paytrace)', 'Dejavoo gateway (iPOSPays or Denovo)', 'SwipeSimple mobile app'];
foreach ($storage->loadByProperties(['type' => 'comparison_table', 'field_eyebrow' => 'Other fees']) as $table) {
  $keep = [];
  $removed = [];
  foreach ($table->get('field_items')->referencedEntities() as $row) {
    if (in_array($row->get('field_label')->value, $dropped)) {
      $removed[] = $row;
    }
    else {
      $keep[] = ['target_id' => $row->id(), 'target_revision_id' => $row->getRevisionId()];
    }
  }
  if ($removed) {
    $table->set('field_items', $keep)->save();
    foreach ($removed as $row) {
      $row->delete();
    }
    $changes[] = 'Other fees: ' . count($removed) . ' gateway and software rows removed';
  }
}

// FAQ answers.
$answers = [
  'Some setups have them, for example dual pricing software, a gateway or Clover platform fees. Every one is listed on your quote before you sign.' => 'Yes, one for each program. It covers your account and PCI compliance, and on dual pricing the gateway too. Clover devices and online gateways add their own monthly fees. Every one is listed on your quote before you sign.',
  'Dual pricing has a monthly software fee, plus any gateway or hardware fees for your setup. Your quote lists every line so there are no surprises.' => 'Dual pricing has one monthly program fee that covers the gateway, your dashboard and PCI compliance, plus any hardware you lease. Your quote lists every line so there are no surprises.',
];
foreach ($storage->loadByProperties(['type' => 'faq_item']) as $faq) {
  $answer = $faq->get('field_answer')->first();
  if (!$answer) {
    continue;
  }
  $text = $answer->value;
  foreach ($answers as $old => $new) {
    $text = str_replace($old, $new, $text);
  }
  // The dual pricing page's hardware answer: dual pricing runs on Dejavoo
  // and Valor, not PAX.
  if (str_contains(strtolower((string) $faq->getParentEntity()?->getParentEntity()?->label()), 'dual pricing') || str_contains(strtolower((string) $faq->getParentEntity()?->label()), 'dual pricing')) {
    $text = str_replace('Many Dejavoo, PAX and Valor terminals can be reprogrammed. If yours cannot,', 'Many Dejavoo and Valor terminals can be reprogrammed for dual pricing. If yours cannot,', $text);
  }
  if ($text !== $answer->value) {
    $faq->set('field_answer', ['value' => $text, 'format' => $answer->format])->save();
    $changes[] = 'FAQ: ' . $faq->get('field_question')->value;
  }
}

// Savings estimator: playbook rates in place of the example ones.
foreach ($storage->loadByProperties(['type' => 'savings_estimator']) as $estimator) {
  if ((float) $estimator->get('field_dp_fee')->value == 19.95 && (float) $estimator->get('field_flat_percent')->value == 2.9) {
    $estimator->set('field_flat_percent', 2.59);
    $estimator->set('field_flat_cents', 10);
    $estimator->set('field_ic_percent', 2.15);
    $estimator->set('field_ic_cents', 20);
    $estimator->set('field_dp_fee', 14.95);
    $estimator->set('field_assumptions', 'Example only. Assumes our 2.59% + 10¢ in-person flat rate, interchange plus at a typical 1.80% + 10¢ card-network cost plus our 0.35% + 10¢ markup, a $25 average ticket and the $14.95 monthly dual pricing program fee. Your real numbers come from your statement.');
    $estimator->save();
    $changes[] = 'savings estimator: playbook rates';
  }
}

// Program names from the pricing playbook (2026-11-21), without the
// Impeccabyte prefix: Simple (flat rate), Cash Price (dual pricing) and Pro
// (interchange plus), wherever a program is offered or compared. The
// comparison table headers use the names alone (2026-11-23). Pages that
// explain the pricing types keep the generic terms.
$names = [
  'Flat rate' => ['Simple', "One rate on every card. Easy to budget while you're getting started.", "A flat rate: one rate on every card. Easy to budget while you're getting started.", 'Get a flat-rate quote', 'Get a Simple quote'],
  'Dual pricing' => ['Cash Price', 'Show a cash price and a card price. Card customers cover the processing cost.', 'Dual pricing: show a cash price and a card price. Card customers cover the processing cost.', NULL, NULL],
  'Interchange plus' => ['Pro', "You pay the card network's actual cost, plus one small markup we show in writing.", "Interchange plus: you pay the card network's actual cost, plus one small markup we show in writing.", 'Get an interchange plus quote', 'Get a Pro quote'],
];
foreach ($storage->loadByProperties(['type' => 'pricing_card']) as $card) {
  $title = $card->get('field_title')->value;
  if (!isset($names[$title])) {
    continue;
  }
  [$name, $old_body, $new_body, $old_link, $new_link] = $names[$title];
  $card->set('field_title', $name);
  $swap($card, 'field_body', $old_body, $new_body);
  $link = $card->get('field_link')->first();
  if ($old_link && $link && $link->title === $old_link) {
    $card->set('field_link', ['uri' => $link->uri, 'title' => $new_link]);
  }
  $card->save();
  $changes[] = "pricing card: $title is now $name";
}
foreach ($storage->loadByProperties(['type' => 'comparison_table']) as $table) {
  $columns = array_column($table->get('field_columns')->getValue(), 'value');
  if ($columns === ['Flat rate', 'Dual pricing', 'Interchange plus'] || $columns === ['Simple (flat rate)', 'Cash Price (dual pricing)', 'Pro (interchange plus)']) {
    $table->set('field_columns', ['Simple', 'Cash Price', 'Pro'])->save();
    $changes[] = 'comparison table: program names in the headers';
  }
}
foreach ($storage->loadByProperties(['type' => 'table_row', 'field_label' => 'Address verification']) as $row) {
  if ($swap($row, 'field_cells', '5¢ each on interchange plus. Included on flat rate and dual pricing.', '5¢ each on Pro. Included on Simple and Cash Price.')) {
    $row->save();
    $changes[] = 'Other fees: address verification names the programs';
  }
}
$setup_names = ['Dual pricing' => 'Cash Price', 'Interchange plus pricing' => 'Pro', 'Flat-rate pricing' => 'Simple'];
foreach ($setup_names as $old => $new) {
  foreach ($storage->loadByProperties(['type' => 'setup_line', 'field_title' => $old]) as $line) {
    $line->set('field_title', $new)->save();
    $changes[] = "Home chip: $old is now $new";
  }
}
foreach ($storage->loadByProperties(['type' => 'setup_line', 'field_note' => 'Simple while you are starting out']) as $line) {
  $line->set('field_note', 'Easy while you are starting out')->save();
  $changes[] = 'Home chip: pop-ups note no longer repeats "Simple"';
}
$faq_names = [
  'and on dual pricing the gateway too.' => 'and on Cash Price the gateway too.',
  'move you to interchange plus or a flat rate. We will review the numbers with you first.' => 'move you to Pro or Simple. We will review the numbers with you first.',
  'move you to dual pricing or a flat rate. We review the math with you first.' => 'move you to Cash Price or Simple. We review the math with you first.',
];
foreach ($storage->loadByProperties(['type' => 'faq_item']) as $faq) {
  $answer = $faq->get('field_answer')->first();
  if ($answer && ($text = strtr($answer->value, $faq_names)) !== $answer->value) {
    $faq->set('field_answer', ['value' => $text, 'format' => $answer->format])->save();
    $changes[] = 'FAQ names the programs: ' . $faq->get('field_question')->value;
  }
}
foreach ($storage->loadByProperties(['type' => 'savings_estimator']) as $estimator) {
  if ($swap($estimator, 'field_assumptions',
    'Example only. Assumes our 2.59% + 10¢ in-person flat rate, interchange plus at a typical 1.80% + 10¢ card-network cost plus our 0.35% + 10¢ markup, a $25 average ticket and the $14.95 monthly dual pricing program fee. Your real numbers come from your statement.',
    'Example only. Assumes Simple at 2.59% + 10¢ in person, Pro at a typical 1.80% + 10¢ card-network cost plus our 0.35% + 10¢ markup, a $25 average ticket and the $14.95 monthly Cash Price program fee. Your real numbers come from your statement.')) {
    $estimator->save();
    $changes[] = 'savings estimator: program names in the assumptions';
  }
}

// An earlier version used "Impeccabyte Simple", "Impeccabyte Cash Price" and
// "Impeccabyte Pro". Drop the prefix wherever it is still there.
$strip = fn(?string $text) => $text === NULL ? NULL : preg_replace('/Impeccabyte (Simple|Cash Price|Pro)\b/', '$1', $text);
$prefixed = [
  'pricing_card' => ['field_title'],
  'setup_line' => ['field_title'],
  'table_row' => ['field_cells'],
  'faq_item' => ['field_answer'],
  'savings_estimator' => ['field_assumptions'],
];
foreach ($prefixed as $type => $fields) {
  foreach ($storage->loadByProperties(['type' => $type]) as $paragraph) {
    $changed = FALSE;
    foreach ($fields as $field) {
      $items = $paragraph->get($field)->getValue();
      foreach ($items as &$item) {
        if (isset($item['value']) && ($new = $strip($item['value'])) !== $item['value']) {
          $item['value'] = $new;
          $changed = TRUE;
        }
      }
      unset($item);
      if ($changed) {
        $paragraph->set($field, $items);
      }
    }
    if ($changed) {
      $paragraph->save();
      $changes[] = "$type: Impeccabyte prefix dropped";
    }
  }
}

// Clover's monthly fees on every Clover device that has none (Clover Mini
// already lists them). Cellular data only where the site says the device
// runs on cellular.
$clover = ib_term_id('manufacturer', 'Clover');
$cellular = ['clover-flex'];
$nids = \Drupal::entityQuery('node')->accessCheck(FALSE)->condition('type', 'device')->condition('field_manufacturer', $clover)->execute();
foreach (\Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids) as $device) {
  // Kitchen displays take no payments, and their monthly fee through
  // Maverick is not in the playbook, so they get no fees here.
  if (!$device->get('field_fees')->isEmpty() || $device->get('field_device_type')->entity?->label() === 'Kitchen Display') {
    continue;
  }
  $fees = [
    ib_paragraph('fee_row', ['label' => 'Clover platform fee', 'amount' => 9.95]),
    ib_paragraph('fee_row', ['label' => 'Security and encryption (TransArmor)', 'amount' => 9.95]),
  ];
  $alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $device->id());
  if (in_array(basename($alias), $cellular)) {
    $fees[] = ib_paragraph('fee_row', ['label' => 'Cellular data, optional', 'amount' => 44.95]);
  }
  $device->set('field_fees', $fees)->save();
  $changes[] = 'Clover fees: ' . $device->label();
}

// BIN fees (2026-11-22). Maverick's sponsor banks charge more on keyed,
// online and specialty volume than on in-person sales. Simple's keyed and
// online rate goes up to cover it, the Pricing FAQ says specialty industries
// are quoted one by one, and the specialty "extra fees" answer says why.
foreach ($storage->loadByProperties(['type' => 'table_row', 'field_label' => 'Rate']) as $row) {
  $cells = array_column($row->get('field_cells')->getValue(), 'value');
  if (($cells[0] ?? NULL) === '2.59% + 10¢ in person. 2.99% + 25¢ keyed or online. American Express included.') {
    $cells[0] = '2.59% + 10¢ in person. 3.29% + 30¢ keyed or online. American Express included.';
    $row->set('field_cells', $cells)->save();
    $changes[] = 'comparison table: Simple keyed and online rate';
  }
}
foreach ($storage->loadByProperties(['type' => 'faq_item']) as $faq) {
  $answer = $faq->get('field_answer')->first();
  if ($answer && $answer->value === '<p>There can be. Specialty accounts may carry monitoring fees and pass-through card-brand fees. Every fee that applies to you is listed on your quote before you sign.</p>') {
    $faq->set('field_answer', ['value' => '<p>There can be. Specialty accounts may carry monitoring fees and pass-through card-brand fees, and they cost more for the sponsor banks to process, so your rates are quoted for your business rather than taken from our standard programs. Every rate and fee is listed on your quote before you sign.</p>', 'format' => $answer->format])->save();
    $changes[] = 'specialty FAQ: extra fees';
  }
}
if ($pricing = ib_node_by_alias('/pricing')) {
  foreach ($pricing->get('field_sections')->referencedEntities() as $section) {
    if ($section->bundle() !== 'faq' || $section->get('field_heading')->value !== 'Pricing questions') {
      continue;
    }
    $questions = array_map(fn($item) => $item->get('field_question')->value, $section->get('field_items')->referencedEntities());
    if (!in_array('Do these prices apply to specialty industries?', $questions, TRUE)) {
      $items = $section->get('field_items')->getValue();
      $items[] = ib_paragraph('faq_item', ['question' => 'Do these prices apply to specialty industries?', 'answer' => ib_html('<p>Not always. Businesses such as CBD sellers, online pharmacies or firearms dealers cost more for the sponsor banks to process, so we quote their rates one by one. Your quote shows every rate and fee before you sign.</p>')]);
      $section->set('field_items', $items)->save();
      $changes[] = 'Pricing FAQ: specialty industries';
    }
  }
}

if ($changes) {
  \Drupal::service('cache_tags.invalidator')->invalidateTags(['rendered', 'node_list']);
}
print $changes ? "Pricing programs:\n- " . implode("\n- ", $changes) . "\n" : "Pricing programs: nothing to change.\n";
