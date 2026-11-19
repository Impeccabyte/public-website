<?php

/**
 * @file
 * The About us page, as designed and approved on 2026-11-19.
 *
 * Says what Impeccabyte is (an Austin agency and authorized sales agent of
 * Maverick Payments), what each party behind a merchant account does, and
 * how working with us goes. The founder story, founder photo, office
 * address, phone and hours stay placeholders until they are supplied; the
 * founder photo is never generated.
 *
 * Safe to re-run. A new site gets the page; a site that still has the short
 * "more about us is on the way" page has its sections replaced; a page
 * edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/89-about.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/about';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$step = fn(string $title, string $body) => ['_type' => 'step', 'title' => $title, 'body' => $body];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$industry = fn(string $title, string $url) => ['_type' => 'feature_card', 'title' => $title, 'link' => ib_link($url, 'See how we help')];

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'About us',
    'heading' => 'Payments help from your neighbors in Austin.',
    'accent_word' => 'neighbors',
    'intro' => "We're an Austin merchant services agency. We help local businesses pick card processing, hardware and software that fit, explain every fee in plain words, and stay your contact long after setup.",
    'link' => ib_link('/get-a-quote', 'Get a quote'),
    'link_secondary' => ib_link($path . '#how-we-work', 'See how we work'),
    'checklist' => ['Based in Austin', 'Every fee in writing', 'Authorized sales agent of Maverick Payments'],
    'media_label' => 'a local specialist and a cafe owner at the counter',
    'show_breadcrumb' => 1,
  ]],
  ['media_split', [
    'eyebrow' => 'Our story',
    'heading' => 'Why we started Impeccabyte',
    'body' => "[Founder story: why Impeccabyte started in [Year founded] and what [Founder name] did before, in the founder's own words. Two or three short paragraphs, signed by [Founder name], founder.]",
    'media_label' => '[Founder name], photo to be supplied',
  ]],
  ['feature_grid', [
    'anchor' => 'how-we-work',
    'eyebrow' => 'How we work',
    'heading' => 'What you can expect from us',
    'intro' => 'Four promises we hold ourselves to with every business we work with.',
    'items' => [
      $card('message-circle', 'Plain English', 'We explain rates, terms and contracts without the jargon, and answer the same question twice if you need us to.'),
      $card('file-text', 'Fees in writing', 'Before you sign, you get every fee and what it is for, written down. No surprises on your first statement.'),
      $card('map-pin', 'Local and reachable', 'We work from Austin. You reach a person who knows your business, not a ticket queue.'),
      $card('badge-check', 'An honest fit', 'We recommend only what your business needs, and we tell you when something is not the right fit.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Who does what',
    'heading' => "Who's behind your merchant account",
    'intro' => "Three parties work together on every account. Here's what each one does, in plain words.",
    'items' => [
      $card('users', 'Impeccabyte, your agent', 'We learn how you sell, recommend a setup, help with the application, set up your equipment and stay your first call for support.'),
      $card('credit-card', 'Maverick Payments, your processor', 'Maverick provides your merchant account, processes your card sales and sends your deposits. Your agreement is with them.'),
      $card('landmark', 'Sponsor banks', 'Maverick works with sponsor banks that connect your account to the card networks. Together they review and approve each application.'),
    ],
  ]],
  ['steps', [
    'eyebrow' => 'Working with us',
    'heading' => 'From first call to first sale',
    'intro' => 'The same four steps for a food truck or a dental office.',
    'items' => [
      $step('Tell us how you sell', 'Counter, phone, website or all three. We ask about your volume, your equipment and what bugs you today.'),
      $step('Get a recommendation', 'We suggest processing, hardware and software that fit, with every fee written down before you decide.'),
      $step('Apply and set up', 'We help you through the application. Once approved, we set up your equipment and show your team how it works.'),
      $step('Keep us on call', 'Questions about a statement, a dispute or a new location come to us. We stay your contact.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Who we serve',
    'heading' => "Built for Austin's small businesses",
    'intro' => 'Small and mid-sized local businesses come first. See how we set up payments for yours.',
    'items' => [
      $industry('Restaurants and bars', '/industries/restaurants'),
      $industry('Food trucks', '/industries/food-trucks'),
      $industry('Retail shops', '/industries/retail'),
      $industry('Salons and spas', '/industries/salons-and-spas'),
      $industry('Home services', '/industries/home-services'),
      $industry('Healthcare and dental', '/industries/healthcare'),
      $industry('Auto repair', '/industries/auto-repair'),
      $industry('Professional services', '/industries/professional-services'),
      $industry('Nonprofits', '/industries/nonprofits'),
      $industry('Pop-ups and markets', '/industries/pop-ups-and-markets'),
      $industry('Specialty industries', '/industries/specialty'),
    ],
  ]],
  ['media_split', [
    'reverse' => 1,
    'eyebrow' => 'Find us',
    'heading' => 'A real person, close by',
    'body' => 'Call, write or stop by. When something goes wrong at the register, you reach someone who knows your setup.',
    'checklist' => [
      'Office: [Street address], Austin, TX [ZIP]',
      'Phone: [Phone number]',
      'Hours: [Business hours]',
    ],
    'link' => ib_link('/get-a-quote', 'Contact us'),
    'media_label' => 'a street of local Austin storefronts',
  ]],
  ['faq', [
    'heading' => 'Questions about us',
    'layout' => 'centered',
    'items' => [
      $faq('Is Impeccabyte a bank or a payment processor?', 'Neither. We are an authorized sales agent of Maverick Payments. Your merchant account is provided by Maverick Payments and its sponsor banks, and we help you choose, apply for and run it.'),
      $faq('Who is Maverick Payments?', 'Maverick Payments is the processor behind your merchant account. They process your card sales, pay your deposits and hold your Merchant Agreement. We are their authorized sales agent in Austin.'),
      $faq('Does Impeccabyte hold my money?', 'No. Deposits are paid by Maverick Payments and its sponsor banks directly to your business bank account. We never hold or move your funds.'),
      $faq('Do you only work with Austin businesses?', '[Confirm: whether we take on businesses outside Austin and Central Texas.]'),
      $faq('How do I reach you?', 'Call us at [Phone number], use the quote form on any page, or stop by our office at [Street address]. A local specialist replies within one business day.'),
    ],
  ]],
  ['cta_band', [
    'heading' => "Let's talk about your business.",
    'accent_word' => 'talk',
    'body' => 'Tell us how you take payments today. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a quote'),
    'link_secondary' => ib_link('/pricing', 'See pricing'),
  ]],
];

$meta = 'Impeccabyte is an Austin merchant services agency and authorized sales agent of Maverick Payments. Plain-English advice, every fee in writing, local support.';
$node = ib_node_by_alias($path);
if (!$node) {
  ib_page('landing_page', 'About us', $path, $sections, ['field_meta_description' => $meta]);
  print "About us page created.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More about us is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  if ($node->hasField('field_meta_description')) {
    $node->set('field_meta_description', $meta);
  }
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "About us page rebuilt from its short version.\n";
}
else {
  print "About us page already built; left as it is.\n";
}
