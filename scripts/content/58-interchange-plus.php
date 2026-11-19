<?php

/**
 * @file
 * The Interchange plus solution page, as designed and approved on 2026-11-19.
 *
 * Explains interchange, assessments and the markup in plain words. No rates,
 * no funding times. Runs after scripts/content/50-resources.php so the
 * "Keep reading" row can point at the library.
 *
 * Safe to re-run. A new site gets the page; a site that still has the short
 * "more detail is on the way" page at /solutions/interchange-plus has its
 * sections replaced; a page edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/58-interchange-plus.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/solutions/interchange-plus';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Interchange plus',
    'heading' => 'Pay the real cost, plus one clear markup.',
    'accent_word' => 'clear',
    'intro' => 'You pay what the card networks charge for each card, plus one small markup we put in writing. No tiers, no padding, and a statement that shows every line.',
    'link' => ib_link('/get-a-quote', 'Get an interchange plus quote'),
    'link_secondary' => ib_link('/pricing#statement', 'Upload a statement'),
    'checklist' => ['Markup in writing', 'Line-by-line statements', 'One price for every customer'],
    'media_label' => 'a shop owner going over a statement at the desk',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'How the price is built',
    'heading' => 'Where every cent of a card fee goes',
    'intro' => 'Every card sale carries three costs. With interchange plus, you see all three, and only one of them is ours.',
    'items' => [
      $card('landmark', 'Interchange', 'Paid to the bank that issued the card. It is set by the card networks and depends on the card: debit usually costs less than a rewards credit card.'),
      $card('globe', 'Assessments', 'A small fee the card network charges on every sale. It is the same whoever processes your cards.'),
      $card('circle-check', 'Our markup', 'One fixed percentage and per-sale amount, agreed in writing before you sign. It is the only part we set.'),
    ],
  ]],
  ['media_split', [
    'eyebrow' => 'Your statement',
    'heading' => 'A statement you can actually read',
    'body' => 'Interchange plus statements are longer, because they show everything. We sit down with you for the first one so every line makes sense.',
    'checklist' => [
      'Each card type listed with what the network charged',
      'Our markup on its own line, matching your quote',
      'Monthly fees listed by name, never bundled',
    ],
    'link' => ib_link('/resources/read-a-processing-statement', 'Read the statement guide'),
    'media_label' => 'an owner and a specialist going over a statement together',
  ]],
  ['feature_grid', [
    'eyebrow' => 'Is it a fit?',
    'heading' => 'When interchange plus usually costs least',
    'intro' => "We compare all three programs on your real statement and recommend the cheapest one, even if it isn't this one.",
    'items' => [
      $card('chart-line', 'Steady, higher volume', 'The more you process each month, the more a small, fixed markup saves over a flat rate.'),
      $card('credit-card', 'Lots of debit cards', 'Debit usually costs much less than credit. With interchange plus, you keep that difference.'),
      $card('scale', 'One price for everyone', "You'd rather not show a cash price and a card price, but still want to pay the true cost."),
      $card('file-text', 'You like the detail', 'You or your bookkeeper want every card type and fee on the statement, line by line.'),
    ],
  ]],
  ['steps', [
    'eyebrow' => 'How switching works',
    'heading' => 'From your old statement to your first new one',
    'items' => [
      $step('Send us a statement', 'A recent statement from any processor. A photo or PDF is fine.'),
      $step('We work out your true cost', 'We separate interchange and assessments from what you are being charged on top.'),
      $step('Get a quote in writing', 'One markup, every monthly fee by name, and a side-by-side with your current bill.'),
      $step('We review your first statement', 'After your first month we go through the new statement with you, line by line.'),
    ],
  ]],
  ['callout', [
    'callout_variant' => 'brand',
    'title' => 'No "non-qualified" surprises.',
    'text' => ib_html('<p>Some processors sort cards into tiers and charge more when a card lands in a pricier one, often without saying why. Interchange plus has no tiers: each card costs what the network charges, plus the same markup.</p>'),
  ]],
  ['faq', [
    'heading' => 'Interchange plus questions',
    'layout' => 'centered',
    'items' => [
      $faq('What is interchange?', "Interchange is the fee paid to the bank that issued your customer's card. The card networks set the rates, and they depend on the type of card and how it was taken. Every processor passes it on; interchange plus simply shows it."),
      $faq('Why does my cost change from month to month?', 'Because your card mix changes. A month with more rewards credit cards costs a little more than a month with more debit cards. Our markup stays the same.'),
      $faq('Can the markup change after I sign?', 'Your markup is in your agreement in writing. If anything about your pricing ever changes, we tell you first and explain why.'),
      $faq('Are there monthly fees too?', 'Usually a small set of monthly fees, such as for your gateway or PCI compliance. Your quote lists each one by name so nothing shows up unexplained.'),
      $faq('Can I switch to dual pricing later?', 'Yes. If your numbers change, we can move you to dual pricing or a flat rate. We review the math with you first.'),
      $faq('How fast will I get my money?', 'Deposit timing depends on your account and on when you close your batch each day. Funding cutoffs vary by processor, and we walk you through yours when we set you up.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['read-a-processing-statement', 'interchange-assessments-markup', 'funding-times-explained']))),
  ]],
  ['cta_band', [
    'heading' => "See what you're really paying.",
    'accent_word' => 'really',
    'body' => "Send a recent statement. We'll break down your true cost and show, in writing, what interchange plus would change.",
    'link' => ib_link('/get-a-quote', 'Get an interchange plus quote'),
    'link_secondary' => ib_link('/pricing#statement', 'Upload a statement'),
  ]],
];

$meta = 'Interchange plus pricing: the card networks\' real cost plus one markup in writing, with statements that show every line. Set up and supported from Austin.';
$node = ib_node_by_alias($path);
if (!$node) {
  ib_page('solution', 'Interchange plus', $path, $sections, [
    'field_category' => ['target_id' => ib_term_id('solution_category', 'Lower your costs')],
    'field_short_description' => 'Pass-through pricing with one clear markup',
    'field_icon' => 'chart-line',
    'field_meta_description' => $meta,
  ]);
  print "Interchange plus page created.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  $node->set('field_meta_description', $meta);
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "Interchange plus page rebuilt from its short version.\n";
}
else {
  print "Interchange plus page already built; left as it is.\n";
}
