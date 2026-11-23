<?php

/**
 * @file
 * Specialty requirements from Maverick's addenda, 2026-11-22.
 *
 * Sources: the v202603 merchant agreement addenda and the credit matrix
 * (2026-10-01) in Maverick's knowledge base, summarized in
 * docs/maverick-offerings.md, section 10. Fee amounts in the addenda are
 * left out, as on every specialty page: fees are named and listed on the
 * quote. Nothing promises approval.
 *
 * - Firearms, Dating, Collections, Credit Repair and Lending: the
 *   "Requirements are being confirmed" placeholder is replaced with the same
 *   sections the other verticals have (what to expect, what underwriting
 *   asks for, a second requirements grid, fees, any extra callout and the
 *   "not legal advice" warning), and the hero gets a lead and checklist line
 *   to match.
 * - The other seven verticals get the requirements their addenda add beyond
 *   the first table: cards are appended to an existing grid only when no
 *   card with that title is there, so nothing edited in the admin is lost.
 *
 * Runs after scripts/content/60-specialty.php and 87-specialty-verticals.php,
 * on a fresh install and on an existing site alike. Safe to re-run. Run it
 * with
 *   ./scripts/drush php:script scripts/content/88-specialty-requirements.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];

// Fee lines, worded without amounts, as in 60-specialty.php.
$fee_url = $card('search', 'Website monitoring', 'A monthly monitoring fee for each website address may apply.');
$fee_registration = $card('badge-check', 'Card-brand registration', 'Yearly Visa and Mastercard registration fees may apply. They are pass-through fees set by the card brands.');
$fee_mc_transaction = $card('credit-card', 'Mastercard per-sale fee', 'A Mastercard fee on each sale may apply. It is a pass-through fee set by the card brand.');
$fee_visa_auth = $card('credit-card', 'Visa per-authorization fee', 'A Visa fee on each authorization may apply. It is a pass-through fee set by the card brand.');

// Slug => [addendum, hero lead, underwriting cards, [second grid eyebrow, heading, cards], fee cards, extra callouts as [title, html]].
$five = [
  'firearms' => [
    'Firearm',
    "Firearms merchants are underwritten as a specialty industry under Maverick's firearm addendum. Here is what the review asks for, so you can have it ready.",
    [
      $card('file-check', 'Federal Firearms License', 'Have your current FFL and your state and local licenses ready. They need to cover where you operate and where your customers are.'),
      $card('search', 'Dealer license checks', "Verify the FFL of every dealer you ship to, through the ATF's FFL eZ Check or a similar service."),
      $card('user', 'Know your customers', 'Take reasonable steps to identify your customers and confirm they are buying for lawful purposes.'),
    ],
    ['Your site', 'Website requirements', [
      $card('truck', 'FFL-to-FFL shipping disclaimer', 'Your site states that firearms ship only to federally licensed dealers.'),
      $card('circle-alert', 'No shipping to individuals', 'Firearms are never shipped straight to a customer.'),
    ]],
    [$fee_url],
    [['Selling online and accessories', '<p>Not every sponsor bank accepts firearms, and some accept them only in person, with the card present. Ammunition, parts and accessories that do not need an FFL are reviewed under the same addendum. We check which bank fits your business before you apply.</p>']],
  ],
  'dating' => [
    'Dating and related verticals',
    'Dating sites and apps are underwritten as a specialty industry with card-brand registration. Here is what the review asks for.',
    [
      $card('monitor', 'A demo login', 'Give underwriting a demo login to your site or app, so they can see it the way a member does.'),
      $card('file-text', 'Written policies', 'Have your content monitoring and age verification policies in writing.'),
      $card('shield-check', 'Safety protocols', 'Have controls against human trafficking, and a set way to bring in law enforcement if you suspect it.'),
    ],
    ['Your platform', 'Platform requirements', [
      $card('circle-alert', 'No explicit content', 'Your site or app cannot offer explicit adult content or services.'),
      $card('user', 'Age and consent checks', 'Your platform checks that every member is of age and taking part by choice.'),
      $card('badge-check', 'Honest profiles', 'You have controls that keep what members say about who they are true and up to date.'),
    ]],
    [$card('badge-check', 'Card-brand registration', 'Yearly card-brand registration fees may apply. They are pass-through fees set by the card brands.'), $fee_visa_auth, $fee_url],
    [['If you offer a trial', '<p>A free or discounted trial that turns into a paid membership is also reviewed under the card brands\' trial billing rules. Our <a href="/industries/specialty/nutraceuticals-and-subscriptions">subscriptions page</a> explains what that asks for.</p>']],
  ],
  'collections' => [
    'Collection agency',
    "Collection agencies are underwritten as a specialty industry under Maverick's collection agency addendum. Here is what the review asks for.",
    [
      $card('file-check', 'Licenses where debtors live', 'Be licensed in your state and in each state where the people you collect from live.'),
      $card('phone', 'Your call script', 'Have the script your team uses on collection calls ready to share.'),
      $card('list-checks', 'Your debt sources', 'List where your accounts come from, with the agreements or contracts behind them.'),
    ],
    ['Taking payments', 'How payments have to work', [
      $card('credit-card', 'Debit cards only', 'Debts can be paid by debit card, not by credit card.'),
      $card('circle-alert', 'No bought debt', 'You collect for your clients. Buying debt to collect, and collecting payday loans, is not accepted.'),
      $card('x', 'No bounced checks by card', 'Card rules do not allow a card payment to cover a bounced check.'),
      $card('scale', 'Fair collection practices', 'No harassing or misleading practices. The Fair Debt Collection Practices Act, the Fair Credit Reporting Act and the Telephone Consumer Protection Act apply, along with CFPB, FTC and state rules.'),
    ]],
    [$fee_url],
    [['Bank by bank', '<p>Not every sponsor bank accepts collection agencies. We check which one fits your business before you apply.</p>']],
  ],
  'credit-repair' => [
    'Credit repair and related services',
    "Credit repair businesses are underwritten as a specialty industry under Maverick's credit repair addendum. Here is what the review asks for.",
    [
      $card('file-check', 'State licenses and registrations', 'Be licensed or registered where you operate and where your clients live.'),
      $card('scale', 'Credit repair law', 'Your services and contracts follow the Credit Repair Organizations Act and the Telemarketing Sales Rule, along with other federal and state rules.'),
    ],
    ['Marketing and billing', 'How you market and bill', [
      $card('mail', 'No cold outreach', 'No unsolicited email and no outbound telemarketing.'),
      $card('circle-alert', 'Claims you can back up', 'No promised results you cannot support, no fake testimonials or made-up deadlines, and no celebrity names or news logos in your ads.'),
      $card('receipt', 'Clear charges', 'Bill only for what your payment page, confirmation page and terms show, and stop recurring charges when a client asks.'),
      $card('x', 'No free trials', 'No offer advertised as a free or risk-free trial.'),
    ]],
    [$fee_url],
    [],
  ],
  'lending' => [
    'Short term loan',
    "Lenders are underwritten as a specialty industry under Maverick's short-term loan addendum. Here is what the review asks for.",
    [
      $card('file-check', 'Licenses for every state', 'Hold the lending licenses for where you operate and, where the law requires it, where your borrowers live. Tell us right away if one lapses.'),
      $card('file-text', 'Compliance documents', 'Have your compliance policies and procedures, state filings and Truth in Lending disclosures ready.'),
      $card('search', 'An outside compliance audit', 'Underwriting may ask for a compliance audit by an independent firm.'),
    ],
    ['Your loans', 'What your loans have to look like', [
      $card('circle-alert', 'No payday loans', 'Payday loans are not accepted.'),
      $card('map-pin', 'US borrowers only', 'You lend only within the United States, with no tribal or foreign lending.'),
      $card('landmark', 'Your own loans', 'You only take repayments on loans you made yourself.'),
      $card('file-text', 'Terms in plain view', 'Your site, ads and loan agreements show every fee, rate, finance charge, payment schedule and penalty before the borrower signs.'),
      $card('credit-card', 'Card rules for repayment', 'Visa credit cards cannot be used to repay a loan, and Discover has its own rules for loan repayments.'),
    ]],
    [$fee_url],
    [['Loans under 12 months', '<p>Some sponsor banks do not accept loans shorter than 12 months. We check which bank fits your business before you apply.</p>']],
  ],
];

// Slug => grid heading => cards to add, plus extra callouts and replaced card bodies.
$more = [
  'smoke-and-vape' => [
    'grids' => [
      'What underwriting asks for' => [
        $card('badge-check', 'FDA marketing authorization', 'Every tobacco product has FDA marketing authorization or a granted exemption.'),
        $card('store', 'In store and online, approved separately', 'In-store and online sales are approved separately. Online sales need card-brand registration.'),
      ],
      'Website requirements' => [
        $card('circle-alert', 'No CBD or kratom without approval', 'Products such as CBD or kratom need approval before you sell them.'),
      ],
      'Fees that may apply' => [$fee_registration],
    ],
    'bodies' => [
      'Nicotine warning on every product' => ['Each product needs a nicotine warning.', 'Each product carries this warning: "WARNING: This product contains nicotine. Nicotine is an addictive chemical."'],
    ],
  ],
  'kratom' => [
    'grids' => [
      'What underwriting asks for' => [
        $card('mail', 'Regulator letters shared', 'Send us any FDA or other regulator warning letter within 5 business days.'),
      ],
      'Website requirements' => [
        $card('x', 'No 7-OH products', 'Products made with 7-OH are not accepted, and sales stop right away anywhere a product becomes illegal.'),
      ],
    ],
  ],
  'adult-content' => [
    'grids' => [
      'What underwriting asks for' => [
        $card('file-check', 'Agreements with content providers', 'If other people upload content, have written agreements with each of them and check their age and identity.'),
      ],
      'Fees that may apply' => [$fee_mc_transaction],
    ],
    'callouts' => [['What underwriting accepts', "<p>Maverick's credit policy accepts studio-produced and webcam content. Content-creator platforms and AI-generated content are not accepted.</p>"]],
  ],
  'gaming' => [
    'grids' => [
      'What underwriting asks for' => [
        $card('badge-check', 'Third-party certification', 'An independent firm certifies that your age and location checks work.'),
        $card('file-text', 'A written compliance program', 'Written policies for identity, age and location checks, sanctions and exclusion lists, limits and monitoring, and keeping banned players out.'),
      ],
      'Website requirements' => [
        $card('credit-card', 'No cash or anonymous prepaid cards', 'Players cannot pay in cash or with anonymous prepaid cards.'),
      ],
      'Fees that may apply' => [
        $fee_registration,
        $card('badge-check', 'Visa merchant verification', 'Some gaming categories need Visa merchant verification, which has a one-time fee.'),
      ],
    ],
  ],
  'online-pharmacy-and-telehealth' => [
    'grids' => [
      'What underwriting asks for' => [
        $card('badge-check', 'Pharmacy accreditation', 'Be accredited by the National Association of Boards of Pharmacy (such as VIPPS) or certified by LegitScript.'),
        $card('file-check', 'DEA registration', 'Be registered with the DEA where required, and follow the Ryan Haight Act for prescriptions sold online.'),
      ],
      'Fees that may apply' => [$fee_mc_transaction],
    ],
  ],
];

$changed = 0;
$sections_of = fn($node) => $node->get('field_sections')->referencedEntities();
$card_titles = fn($grid) => array_map(fn($item) => $item->get('field_title')->value, $grid->get('field_items')->referencedEntities());
$warning_title = 'Rules change, and they differ by state';
$callout = fn(string $variant, string $title, string $html) => ib_paragraph('callout', ['callout_variant' => $variant, 'title' => $title, 'text' => ib_html($html)]);

// The five placeholder pages.
foreach ($five as $slug => [$addendum, $lead, $underwriting, [$eyebrow, $heading, $second], $fees, $extras]) {
  $node = ib_node_by_alias('/industries/specialty/' . $slug);
  if (!$node) {
    continue;
  }
  $sections = [];
  $replaced = FALSE;
  foreach ($sections_of($node) as $section) {
    if ($section->bundle() === 'callout' && $section->get('field_title')->value === 'Requirements are being confirmed') {
      $sections[] = $callout('brand', 'What to expect', '<p>Specialty underwriting takes more paperwork and more review than a standard account. A reserve may be required and extra fees may apply. Knowing that up front lets you arrive with the right documents.</p>');
      $sections[] = ib_paragraph('feature_grid', ['eyebrow' => 'Paperwork', 'heading' => 'What underwriting asks for', 'items' => $underwriting]);
      $sections[] = ib_paragraph('feature_grid', ['eyebrow' => $eyebrow, 'heading' => $heading, 'items' => $second]);
      $sections[] = ib_paragraph('feature_grid', ['eyebrow' => 'Costs', 'heading' => 'Fees that may apply', 'intro' => 'Amounts are listed on your quote. Nothing is charged that you have not seen in writing.', 'items' => $fees]);
      foreach ($extras as [$title, $html]) {
        $sections[] = $callout('info', $title, $html);
      }
      $sections[] = $callout('warning', $warning_title, '<p>This page describes what underwriting asks for. It is not legal advice. Staying compliant with state and federal law is your responsibility, so check current state rules for your business before you sell.</p>');
      $replaced = TRUE;
      continue;
    }
    if ($section->bundle() === 'hero') {
      if (str_ends_with((string) $section->get('field_intro')->value, 'are underwritten as a specialty industry under their own Maverick addendum.')) {
        $section->set('field_intro', $lead);
      }
      $checklist = array_map(fn(array $item) => $item['value'] === 'Requirements being confirmed' ? 'Requirements laid out up front' : $item['value'], $section->get('field_checklist')->getValue());
      $section->set('field_checklist', $checklist);
      $section->save();
    }
    $sections[] = $section;
  }
  if (!$replaced) {
    continue;
  }
  $node->set('field_sections', $sections);
  $node->save();
  $changed++;

  // Record the addendum on the industry term.
  if (($tid = ib_term_id('industry', $node->label())) && ($term = \Drupal\taxonomy\Entity\Term::load($tid)) && $term->hasField('field_maverick_addendum') && !$term->get('field_maverick_addendum')->value) {
    $term->set('field_maverick_addendum', $addendum);
    $term->save();
  }
}

// The hub's lending card line.
if ($hub = ib_node_by_alias('/industries/specialty')) {
  foreach ($sections_of($hub) as $section) {
    if ($section->bundle() !== 'feature_grid') {
      continue;
    }
    foreach ($section->get('field_items')->referencedEntities() as $item) {
      if ($item->get('field_body')->value === 'Lenders taking card payments.') {
        $item->set('field_body', 'Lenders taking loan repayments by card.');
        $item->save();
        $changed++;
      }
    }
  }
}

// The other seven: add what their addenda ask beyond the first table.
foreach ($more as $slug => $spec) {
  $node = ib_node_by_alias('/industries/specialty/' . $slug);
  if (!$node) {
    continue;
  }
  $sections = $sections_of($node);
  foreach ($sections as $section) {
    if ($section->bundle() !== 'feature_grid') {
      continue;
    }
    $grid_heading = $section->get('field_heading')->value;
    $add = array_values(array_filter($spec['grids'][$grid_heading] ?? [], fn(array $item) => !in_array($item['title'], $card_titles($section), TRUE)));
    foreach ($section->get('field_items')->referencedEntities() as $item) {
      [$old, $new] = $spec['bodies'][$item->get('field_title')->value] ?? [NULL, NULL];
      if ($old !== NULL && $item->get('field_body')->value === $old) {
        $item->set('field_body', $new);
        $item->save();
        $changed++;
      }
    }
    if ($add) {
      $items = $section->get('field_items')->getValue();
      foreach ($add as $item) {
        $type = $item['_type'];
        unset($item['_type']);
        $items[] = ib_paragraph($type, $item);
      }
      $section->set('field_items', $items);
      $section->save();
      $changed++;
    }
  }

  $titles = array_map(fn($section) => $section->bundle() === 'callout' ? $section->get('field_title')->value : NULL, $sections);
  $new_callouts = array_filter($spec['callouts'] ?? [], fn(array $callout_spec) => !in_array($callout_spec[0], $titles, TRUE));
  if ($new_callouts) {
    $list = [];
    foreach ($sections as $section) {
      if ($section->bundle() === 'callout' && $section->get('field_title')->value === $warning_title) {
        foreach ($new_callouts as [$title, $html]) {
          $list[] = $callout('info', $title, $html);
        }
        $new_callouts = [];
      }
      $list[] = $section;
    }
    $node->set('field_sections', $list);
    $changed++;
  }
  $node->save();
}

print "Specialty requirements: $changed change(s).\n";
