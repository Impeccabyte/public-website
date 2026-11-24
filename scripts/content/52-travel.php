<?php

/**
 * @file
 * Travel, Impeccabyte's second business line (2026-11-23).
 *
 * Two pages, from the approved design (docs/travel-design-prompt.md):
 * /travel, the overview, and /travel/hotel-perks. Facts and limits come
 * from docs/travel-fora-research.md:
 *
 * - Impeccabyte is an independent travel advisor hosted by Fora Travel, not
 *   Fora. No Fora logo, and no hotel brand, consortium or cruise line names.
 * - Perks are examples that vary by hotel. Upgrades and early check-in or
 *   late check-out are only ever "when available". Never "guaranteed",
 *   "VIP" or "free breakfast everywhere".
 * - In most cases the rate is the same as booking direct. There is no
 *   planning fee (Ry, 2026-11-24); never "no fees" or "lowest price", which
 *   would cover costs we don't control.
 * - Clients can use the loyalty points from their favorite hotel and travel
 *   programs (Ry, 2026-11-24).
 * - Travel is only for businesses with an active Impeccabyte merchant
 *   account, booked through their account manager in the merchant app. There
 *   is no booking form; the main button is "Log in".
 * - The seller-of-travel disclosure closes every travel page, with Fora's
 *   registrations and licenses (supplied 2026-11-24).
 *
 * Menu links (Services > Travel) and the footer column are in 10-chrome.php.
 *
 * Safe to re-run: a page that already exists at its address is left alone.
 * Run it with
 *   ./scripts/drush php:script scripts/content/52-travel.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$login = 'https://app.impeccabyte.com';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$card = fn(string $icon, string $title, string $body, string $note = '') => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body] + ($note ? ['note' => $note] : []);
$photo = fn(string $title, string $body, string $label) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'media_label' => $label];

$eligibility = ['callout', [
  'callout_variant' => 'brand',
  'title' => 'For Impeccabyte merchants',
  'text' => ib_html('<p>Travel planning is for businesses with an active Impeccabyte merchant account, and you book it through your account manager. Not a customer yet? <a href="/get-a-quote">Get a quote</a>.</p>'),
]];
$disclosure = ['callout', [
  'callout_variant' => 'info',
  'title' => 'About our travel services',
  'text' => ib_html('<p>Impeccabyte is an independent contractor of Fora Travel, Inc., a registered seller of travel. Fora Travel, Inc. holds the following registrations and licenses:</p><ul><li>California: Seller of Travel Registration No. 2151995-50 (CST 2151995-50)</li><li>Florida: Seller of Travel Registration No. ST43973</li><li>Washington: Seller of Travel Unified Business ID (UBI) 605329242</li><li>British Columbia: CPBC License No. 88694</li><li>Ontario: TICO Registration No. 50027942</li><li>United Kingdom: ICO Registration No. ZC157277</li></ul><p>Registration as a seller of travel does not constitute approval by the State of California.</p>'),
]];

// Overview.
$overview = ib_page('landing_page', 'Travel', '/travel', [
  ['hero', [
    'hero_variant' => 'split',
    'hero_art' => 'confirmation',
    'eyebrow' => 'Travel planning for our merchants',
    'heading' => 'Better hotel stays, planned by a real person.',
    'accent_word' => 'real',
    'intro' => "We're an independent travel advisor hosted by Fora Travel. Tell us where you'd like to go, and we'll plan it and book partner hotels with perks such as daily breakfast, a hotel credit and a welcome amenity, depending on the hotel. In most cases you pay the same rate as booking direct.",
    'link' => ib_link($login, 'Log in'),
    'link_secondary' => ib_link('/travel/hotel-perks', 'See hotel perks'),
    'checklist' => ['Perks at partner hotels', 'Same rate as direct, in most cases', 'No planning fee'],
  ]],
  $eligibility,
  ['steps', [
    'eyebrow' => 'How it works',
    'heading' => 'Three steps from idea to check-in',
    'items' => [
      $step('Tell your account manager', "Where, when, who's coming and roughly what you'd like to spend. They take it from there."),
      $step('We suggest a plan', "Hotel options with each one's perks spelled out, so you can choose before we book."),
      $step('We book it with you', 'You get each confirmation with its perks listed, and you have one person to ask about changes before you go.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'At partner hotels',
    'heading' => 'What you can expect when you stay',
    'intro' => 'Perks vary by hotel. We confirm what yours includes before you book, so nothing is a surprise at the front desk.',
    'items' => [
      $card('coffee', 'Daily breakfast', 'Breakfast each morning at many partner hotels.', 'Depends on the hotel'),
      $card('gift', 'A hotel credit', 'Money to spend at the hotel, often on dining or the spa. The amount varies.', 'Depends on the hotel'),
      $card('bed', 'A room upgrade', 'We ask on every booking. The hotel decides when you arrive.', 'When available'),
      $card('clock', 'Early check-in or late check-out', 'More time in your room at either end, if the hotel can manage it that day.', 'When available'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Trips we plan',
    'heading' => "Work trips, family trips and the ones you've waited for",
    'items' => [
      $photo('Business travel', "A good night's sleep near the meeting, and breakfast before it, where the hotel includes it.", 'a hotel room in soft morning light'),
      $photo('Family trips', 'Rooms that fit everyone, and a plan that works for the kids and the grown-ups.', 'a family breakfast table by a window'),
      $photo('Celebrations', "Honeymoons, anniversaries and big birthdays, with the hotel told why you're there.", 'a quiet old-town street at golden hour'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'What it costs',
    'heading' => 'The price, in plain words',
    'intro' => "We tell you what you'll pay before we book anything.",
    'items' => [
      $card('bed', 'Hotel stays', 'In most cases you pay the same rate as booking direct, with any perks the hotel offers through Fora Travel on top.'),
      $card('receipt', 'Planning', 'For bigger trips with flights, restaurants or several stops, we plan it all with you, with no planning fee.'),
    ],
  ]],
  ['faq', [
    'eyebrow' => 'Questions',
    'heading' => 'Good questions, straight answers',
    'layout' => 'centered',
    'items' => [
      $faq('Who can use travel planning?', 'Businesses with an active Impeccabyte merchant account. Already a customer? Your account manager books your travel. Not a customer yet? <a href="/get-a-quote">Get a quote</a> for card processing first.'),
      $faq('How do you work with Fora Travel?', "We're an independent travel advisor hosted by Fora Travel. Fora is the registered travel agency we book through; we're the people who plan your trip and answer your questions."),
      $faq('Will I pay more than booking myself?', 'In most cases the hotel rate is the same as booking direct, and we don\'t charge a planning fee, even for trips with flights or several stops.'),
      $faq('Is an upgrade guaranteed?', "No. We ask for one on every booking, and the hotel decides when you arrive based on what's free. Early check-in and late check-out work the same way."),
      $faq('Can I use my loyalty points?', 'Yes. You can use the loyalty points from your favorite hotel and travel programs on the trips we plan with you.'),
    ],
  ]],
  ['cta_band', [
    'heading' => "Tell us where you'd like to go.",
    'accent_word' => 'like',
    'body' => 'Log in to your merchant account to reach your account manager. They plan it with you and come back with ideas, hotel options and what any planning would cost.',
    'link' => ib_link($login, 'Log in'),
    'link_secondary' => ib_link('/travel/hotel-perks', 'See hotel perks'),
  ]],
  $disclosure,
]);

// Hotel perks.
$perks = ib_page('landing_page', 'Hotel Perks', '/travel/hotel-perks', [
  ['hero', [
    'hero_variant' => 'split',
    'hero_art' => 'confirmation',
    'heading' => 'What partner hotels usually include',
    'accent_word' => 'usually',
    'intro' => "When we book a participating property through Fora Travel, your stay often comes with extras you wouldn't get booking yourself. Here's what to expect, and what to treat as a nice surprise.",
    'link' => ib_link($login, 'Log in'),
    'link_secondary' => ib_link('/travel', 'See how planning works'),
    'show_breadcrumb' => 1,
  ]],
  $eligibility,
  ['feature_grid', [
    'eyebrow' => 'Depends on the hotel',
    'heading' => 'Perks such as these, written into your booking',
    'intro' => "Each hotel sets its own package. Some include all of these, some only one or two. We list your hotel's perks before you book.",
    'items' => [
      $card('coffee', 'Daily breakfast', 'Breakfast each morning for the guests on the booking, at many partner hotels.'),
      $card('credit-card', 'A hotel credit', 'Money to spend during your stay, often on dining, drinks or the spa. The amount and what it covers depend on the hotel.'),
      $card('gift', 'A welcome amenity', 'A small welcome from the hotel, like a drink or a treat in your room, at some properties.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'When available',
    'heading' => 'We ask for these on every booking',
    'intro' => "They're never guaranteed. The hotel decides on the day, based on what rooms are free.",
    'items' => [
      $card('bed', 'A room upgrade', 'A better room than the one you booked, if one is free when you arrive.'),
      $card('clock', 'Early check-in or late check-out', 'More time in your room at either end, if the hotel can manage it that day.'),
    ],
  ]],
  ['callout', [
    'callout_variant' => 'info',
    'title' => 'Good to know',
    'text' => ib_html("<ul><li>Perks come from the hotel and can change. We check your hotel's current perks when we book.</li><li>In most cases the rate is the same as booking direct, and there's no planning fee.</li><li>Not every hotel is a partner. If yours isn't, we'll tell you before you book.</li><li>You can use the loyalty points from your favorite hotel and travel programs.</li></ul>"),
  ]],
  ['cta_band', [
    'heading' => 'Have a hotel in mind?',
    'accent_word' => 'mind',
    'body' => "Log in and tell your account manager which one and your dates. They'll check whether it's a partner and what perks it includes.",
    'link' => ib_link($login, 'Log in'),
    'link_secondary' => ib_link('/travel', 'See how planning works'),
  ]],
  $disclosure,
]);

// Search titles and descriptions, only while empty.
$meta = [
  [$overview, 'Travel planning with hotel perks for our merchants | Impeccabyte', 'Travel planning for Impeccabyte merchants, as an independent advisor hosted by Fora Travel: partner-hotel perks, in most cases at the same rate as booking direct.'],
  [$perks, 'Hotel perks at partner hotels, explained | Impeccabyte', 'What partner hotels booked through Fora Travel usually include, such as breakfast and a hotel credit, and which perks are only when available.'],
];
foreach ($meta as [$node, $title, $description]) {
  $changed = FALSE;
  if ($node->hasField('field_search_title') && $node->get('field_search_title')->isEmpty()) {
    $node->set('field_search_title', $title);
    $changed = TRUE;
  }
  if ($node->hasField('field_meta_description') && $node->get('field_meta_description')->isEmpty()) {
    $node->set('field_meta_description', $description);
    $changed = TRUE;
  }
  if ($changed) {
    $node->save();
  }
}

print "Travel pages are in place.\n";
