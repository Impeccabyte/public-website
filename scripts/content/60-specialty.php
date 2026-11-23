<?php

/**
 * @file
 * Specialty industries: the terms, the hub at /industries/specialty and one
 * page per vertical, each with the pre-qualification form.
 *
 * Every requirement and fee line comes from the table in the "Specialty
 * industries" section of the information architecture plan, read on
 * 2026-11-18, which summarizes the March 2026 Maverick addenda. Nothing is
 * added from memory. Fee amounts are left out: pricing is not public yet and
 * pass-through card-brand fees are stated only as "may apply". Firearms,
 * Dating, Collections, Credit repair and Lending start with a placeholder
 * that scripts/content/88-specialty-requirements.php replaces with their
 * addenda requirements.
 *
 * Tone: calm and factual. No page promises approval or says there are no
 * reserves, and none gives legal advice.
 *
 * Safe to re-run: a page that already exists at its address is left alone.
 * Run it with
 *   ./scripts/drush php:script scripts/content/60-specialty.php
 */

use Drupal\taxonomy\Entity\Term;

require_once dirname(__DIR__) . '/setup/_helpers.php';

$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$card = fn(string $icon, string $title, string $body, ?array $link = NULL) => array_filter(['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body, 'link' => $link]);

// Fee lines, worded without amounts.
$fee_url = $card('search', 'Website monitoring', 'A monthly monitoring fee for each website address may apply.');
$fee_registration = $card('badge-check', 'Card-brand registration', 'Yearly Visa and Mastercard registration fees may apply. They are pass-through fees set by the card brands.');
$fee_mc_transaction = $card('credit-card', 'Mastercard per-sale fee', 'A Mastercard fee on each sale may apply. It is a pass-through fee set by the card brand.');
$fee_visa_auth = $card('credit-card', 'Visa per-authorization fee', 'A Visa fee on each authorization may apply. It is a pass-through fee set by the card brand.');
$fee_negative_option = $card('badge-check', 'Negative option registration', 'Registration fees for negative option billing may apply.');

// Slug => [term name, addendum, icon, card line, hero lead, underwriting cards, website cards, fee cards, extra callout].
$verticals = [
  'hemp-and-cbd' => ['Hemp & CBD', 'Hemp/CBD', 'leaf', 'Hemp and CBD products, online or in store.',
    'Merchant accounts for hemp and CBD sellers go through extra underwriting. Here is what the review asks for, so you can have it ready.',
    [$card('list-checks', 'A product list', 'Have a full list of the products you sell ready when you apply.'), $card('file-check', 'Lab results on request', 'Be able to supply lab results within 3 days of a request.')],
    [$card('user', 'Age verification', 'Your site has to verify the age of your customers.'), $card('circle-alert', 'No health claims', 'Product pages and marketing cannot make health claims.'), $card('mail', 'No unsolicited marketing', 'No unsolicited email or telemarketing.')],
    [$fee_url],
    ['If you sell HHC products', 'HHC has its own certification. Certificates of Analysis have to be available to customers, age is checked before delivery, and online sales need geofencing. A monthly monitoring fee for each website address may apply.'],
  ],
  'smoke-and-vape' => ['Smoke & Vape', 'Tobacco, nicotine, e-cig', 'store', 'Tobacco, nicotine and e-cigarette retailers.',
    'Smoke and vape shops are underwritten as a specialty industry. Here is what the review asks for before you apply.',
    [$card('file-check', 'Certificate of Analysis and safety data sheet', 'Have a Certificate of Analysis and a safety data sheet for your products.')],
    [$card('triangle-alert', 'Nicotine warning on every product', 'Each product needs a nicotine warning.'), $card('circle-alert', 'No sales to minors statement', 'Your site states that you do not sell to minors.'), $card('user', 'Age verification software', 'Your site uses age verification software.')],
    [$fee_mc_transaction],
    NULL,
  ],
  'kratom' => ['Kratom', 'Kratom', 'leaf', 'Kratom sellers, online or in store.',
    'Kratom merchants go through extra underwriting. Here is what the review asks for, so the paperwork does not slow you down.',
    [$card('file-check', 'Product testing on request', 'Be able to supply product testing within 10 days of a request.')],
    [$card('list-checks', 'Ingredients listed', 'Every product lists its ingredients.'), $card('circle-alert', 'No "incense" framing', 'Products cannot be presented as incense.'), $card('circle-alert', 'No free trials', 'Free trial offers are not allowed.')],
    [$fee_url],
    NULL,
  ],
  'adult-content' => ['Adult Content', 'Adult Content', 'lock', 'Adult content websites.',
    'Adult content merchants are underwritten as a specialty industry with card-brand registration. Here is what the review asks for.',
    [$card('file-text', 'Monthly content reporting', 'Be ready to report on your content each month.')],
    [$card('user', 'Age and consent controls', 'Your site has controls for age and consent.'), $card('message-circle', 'Complaint and takedown process', 'Your site has a clear way to complain and to have content taken down.')],
    [$fee_registration, $fee_visa_auth],
    NULL,
  ],
  'gaming' => ['Gaming', 'Gaming', 'sparkles', 'Online gaming businesses.',
    'Gaming merchants go through extra underwriting, state by state. Here is what the review asks for.',
    [$card('scale', 'A legal opinion for each state', 'Have a legal opinion covering each state you operate in.')],
    [$card('map-pin', 'Age and location controls', 'Your site controls who can play by age and by location.')],
    [$fee_url],
    NULL,
  ],
  'online-pharmacy-and-telehealth' => ['Online Pharmacy & Telehealth', 'Card-absent pharmacy', 'heart-pulse', 'Online pharmacies and telehealth providers.',
    'Online pharmacy and telehealth merchants are underwritten as a specialty industry with card-brand registration. Here is what the review asks for.',
    [$card('truck', 'State shipping rules', 'Show that you follow the shipping rules of each state you ship to.')],
    [$card('user', 'Age and ID verification', 'Your site verifies the age and identity of your customers.')],
    [$fee_registration],
    NULL,
  ],
  'nutraceuticals-and-subscriptions' => ['Nutraceuticals & Subscriptions', 'Marketing practices; Negative option trial', 'repeat', 'Supplements and subscription or trial offers.',
    'Supplement sellers and businesses that bill on a subscription or after a trial go through extra underwriting. Here is what the review looks at.',
    [$card('file-text', 'Your marketing practices', 'The review covers how you market and how you bill, including any trial that turns into a paid plan.')],
    [$card('list-checks', 'Ingredients listed', 'Every product lists its ingredients.'), $card('circle-alert', 'No unapproved health claims', 'Product pages and marketing cannot make unapproved health claims.'), $card('circle-check', 'A consent box for recurring charges', 'An unchecked consent box for recurring charges sits beside the buy button.'), $card('x', 'Easy cancellation', 'Customers can cancel easily.')],
    [$fee_negative_option],
    NULL,
  ],
  // Addenda not reviewed yet: these pages carry a placeholder.
  'firearms' => ['Firearms', '', 'shield-check', 'Firearms and accessories retailers.', 'Firearms merchants are underwritten as a specialty industry under their own Maverick addendum.', NULL, NULL, NULL, NULL],
  'dating' => ['Dating', '', 'users', 'Dating sites and apps.', 'Dating merchants are underwritten as a specialty industry under their own Maverick addendum.', NULL, NULL, NULL, NULL],
  'collections' => ['Collections', '', 'file-text', 'Collection agencies.', 'Collections merchants are underwritten as a specialty industry under their own Maverick addendum.', NULL, NULL, NULL, NULL],
  'credit-repair' => ['Credit Repair', '', 'chart-line', 'Credit repair services.', 'Credit repair merchants are underwritten as a specialty industry under their own Maverick addendum.', NULL, NULL, NULL, NULL],
  'lending' => ['Lending', '', 'landmark', 'Lenders taking loan repayments by card.', 'Lending merchants are underwritten as a specialty industry under their own Maverick addendum.', NULL, NULL, NULL, NULL],
];
// The order Ry listed them in.
$order = ['hemp-and-cbd', 'smoke-and-vape', 'kratom', 'firearms', 'adult-content', 'dating', 'gaming', 'collections', 'credit-repair', 'lending', 'nutraceuticals-and-subscriptions', 'online-pharmacy-and-telehealth'];

// Terms: a Specialty parent with one child per vertical.
$parent_id = ib_term('industry', 'Specialty', 20, NULL, ['field_risk_tier' => 'specialty']);
foreach ($order as $weight => $slug) {
  [$name, $addendum] = $verticals[$slug];
  if (!ib_term_id('industry', $name)) {
    Term::create(['vid' => 'industry', 'name' => $name, 'weight' => $weight, 'parent' => $parent_id, 'field_risk_tier' => 'specialty', 'field_maverick_addendum' => $addendum])->save();
  }
}
$term = fn(string $name) => ['target_id' => ib_term_id('industry', $name)];

$shared_faqs = [
  $faq('Will I be approved?', 'We cannot promise approval. Maverick Payments and its sponsor banks make the underwriting decision. Our part is to tell you plainly what the review asks for and to check your website before we send anything in.'),
  $faq('Will there be a reserve?', 'A reserve may be required. If one applies to your account, it is spelled out in writing before you sign.'),
  $faq('How long does underwriting take?', 'It depends on your business and on how complete the paperwork is. Specialty reviews take longer than standard ones, so it helps to have your documents ready.'),
  $faq('Are there extra fees?', 'There can be. Specialty accounts may carry monitoring fees and pass-through card-brand fees, and they cost more for the sponsor banks to process, so your rates are quoted for your business rather than taken from our standard programs. Every rate and fee is listed on your quote before you sign.'),
  $faq('Is this legal advice?', 'No. Laws for these industries change and differ by state. Staying compliant is your responsibility, so check current state rules or talk to a lawyer about your business.'),
];
$form_section = ['webform_embed', [
  'eyebrow' => 'Pre-qualification',
  'heading' => 'Tell us about your business first',
  'intro' => 'A few questions help us see what underwriting will ask for. We review your website before we send an application in.',
  'items' => [
    ['_type' => 'step', 'title' => 'Send your details', 'body' => 'Websites, volume and licenses'],
    ['_type' => 'step', 'title' => 'We review your website', 'body' => 'And tell you what to fix first'],
    ['_type' => 'step', 'title' => 'You apply with the right paperwork', 'body' => 'Maverick and its sponsor banks decide'],
  ],
  'note' => 'Sending this form is not an application.',
  'webform' => ['target_id' => 'specialty_prequal'],
  'anchor' => 'pre-qualify',
]];

// One page per vertical.
foreach ($order as $slug) {
  [$name, $addendum, $icon, $line, $lead, $underwriting, $website, $fees, $extra] = $verticals[$slug];
  $path = '/industries/specialty/' . $slug;
  $sections = [
    ['hero', [
      'hero_variant' => 'centered',
      'eyebrow' => 'Specialty Industries',
      'heading' => $name . ' merchant accounts, explained plainly.',
      'accent_word' => 'plainly',
      'intro' => $lead,
      'link' => ib_link($path . '#pre-qualify', 'Start pre-qualification'),
      'link_secondary' => ib_link('/industries/specialty', 'See all specialty industries'),
      'show_breadcrumb' => 1,
    ]],
  ];
  if ($underwriting === NULL) {
    $sections[] = ['callout', [
      'callout_variant' => 'info',
      'title' => 'Requirements are being confirmed',
      'text' => ib_html('<p>We are confirming what underwriting asks of ' . strtolower($name) . ' businesses, what your website needs and which fees may apply. [Requirements to be supplied from the Maverick addendum.] Until then, send us your details below and a local specialist will walk you through the current paperwork.</p>'),
    ]];
  }
  else {
    $sections[] = ['callout', [
      'callout_variant' => 'brand',
      'title' => 'What to expect',
      'text' => ib_html('<p>Specialty underwriting takes more paperwork and more review than a standard account. A reserve may be required and extra fees may apply. Knowing that up front lets you arrive with the right documents.</p>'),
    ]];
    $sections[] = ['feature_grid', ['eyebrow' => 'Paperwork', 'heading' => 'What underwriting asks for', 'items' => $underwriting]];
    $sections[] = ['feature_grid', ['eyebrow' => 'Your site', 'heading' => 'Website requirements', 'items' => $website]];
    $sections[] = ['feature_grid', ['eyebrow' => 'Costs', 'heading' => 'Fees that may apply', 'intro' => 'Amounts are listed on your quote. Nothing is charged that you have not seen in writing.', 'items' => $fees]];
    if ($extra) {
      $sections[] = ['callout', ['callout_variant' => 'info', 'title' => $extra[0], 'text' => ib_html('<p>' . $extra[1] . '</p>')]];
    }
    $sections[] = ['callout', [
      'callout_variant' => 'warning',
      'title' => 'Rules change, and they differ by state',
      'text' => ib_html('<p>This page describes what underwriting asks for. It is not legal advice. Staying compliant with state and federal law is your responsibility, so check current state rules for your products before you sell.</p>'),
    ]];
  }
  $sections[] = $form_section;
  $sections[] = ['faq', ['heading' => $name . ' questions', 'layout' => 'centered', 'items' => $shared_faqs]];
  $sections[] = ['cta_band', [
    'heading' => 'Protect the account once it is open.',
    'accent_word' => 'Protect',
    'body' => 'Chargeback alerts and dispute help matter most in specialty industries.',
    'link' => ib_link('/solutions/chargeback-protection', 'See chargeback protection'),
    'link_secondary' => ib_link($path . '#pre-qualify', 'Start pre-qualification'),
  ]];
  ib_page('industry_page', $name, $path, $sections, ['field_industry' => $term($name)]);
}

// The hub.
$hub_cards = [];
foreach ($order as $slug) {
  [$name, , $icon, $line] = $verticals[$slug];
  $hub_cards[] = $card($icon, $name, $line, ib_link('/industries/specialty/' . $slug, 'See what it takes'));
}
ib_page('industry_page', 'Specialty Industries', '/industries/specialty', [
  ['hero', [
    'hero_variant' => 'centered',
    'eyebrow' => 'Specialty Industries',
    'heading' => 'Some businesses take more paperwork. We tell you which.',
    'accent_word' => 'more',
    'intro' => 'Hemp, vape, firearms, subscriptions and other specialty industries can get merchant accounts through Maverick Payments, with extra underwriting. We lay out what each one asks for before you apply.',
    'link' => ib_link('/industries/specialty/pre-qualify', 'Start pre-qualification'),
    'link_secondary' => ib_link('/solutions/chargeback-protection', 'See chargeback protection'),
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Industries',
    'heading' => 'Pick your industry',
    'intro' => 'Each page lists what underwriting asks for, what your website needs and which fees may apply.',
    'items' => $hub_cards,
  ]],
  ['callout', [
    'callout_variant' => 'brand',
    'title' => 'What to expect from specialty underwriting',
    'text' => ib_html('<p>These industries are reviewed more closely than a standard account. Expect more paperwork, a review of your website before the application goes in, and more time. A reserve may be required, and monitoring or card-brand fees may apply. Maverick Payments and its sponsor banks make the decision; we help you prepare for it.</p>'),
  ]],
  ['faq', ['heading' => 'Specialty questions', 'layout' => 'centered', 'items' => $shared_faqs]],
  ['cta_band', [
    'heading' => 'Start with a few questions.',
    'accent_word' => 'few',
    'body' => 'Tell us your industry, your websites and your volume. A local specialist replies within one business day.',
    'link' => ib_link('/industries/specialty/pre-qualify', 'Start pre-qualification'),
    'link_secondary' => ib_link('/solutions/chargeback-protection', 'See chargeback protection'),
  ]],
], ['field_industry' => $term('Specialty')]);

// Reached from the Industries menu and the footer, not from Home.
$industries = ib_menu_link('main', 'Industries', 'route:<nolink>', ['weight' => 2]);
ib_menu_link('main', 'Specialty Industries', 'internal:/industries/specialty', ['parent' => $industries, 'weight' => 20, 'icon' => 'shield-check', 'description' => 'Hemp, vape, firearms and other high-review businesses']);
ib_menu_link('footer-industries', 'Specialty Industries', 'internal:/industries/specialty', ['weight' => 10]);

print "Specialty industries are in place.\n";
