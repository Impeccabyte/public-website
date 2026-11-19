<?php

/**
 * @file
 * The Home services industry page, 2026-11-19.
 *
 * Built on the Restaurants and bars page's pattern. The sample setup follows
 * the Home page's home services chip: a Clover Go reader, payment links and
 * invoices, and integrations with accounting software. ACH is named only
 * through the virtual terminal, as on the Online page.
 *
 * Safe to re-run. A site that still has the short "more detail is on the
 * way" page at /industries/home-services has its sections replaced; a page
 * edited since is left alone. Run it with
 *   ./scripts/drush php:script scripts/content/73-home-services.php
 */

require_once dirname(__DIR__) . '/setup/_helpers.php';

$path = '/industries/home-services';
$faq = fn(string $question, string $answer) => ['_type' => 'faq_item', 'question' => $question, 'answer' => ib_html('<p>' . $answer . '</p>')];
$card = fn(string $icon, string $title, string $body) => ['_type' => 'feature_card', 'icon' => $icon, 'title' => $title, 'body' => $body];
$node_ref = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;
$catalog = function (string $type): string {
  $tid = ib_term_id('device_type', $type);
  return $tid ? "/hardware?type[$tid]=$tid" : '/hardware';
};
$setup = fn(string $title, string $body, string $note, string $photo, string $link, string $url) => ['_type' => 'feature_card', 'title' => $title, 'body' => $body, 'note' => $note, 'media_label' => $photo, 'link' => ib_link($url, $link)];
$solution = fn(string $alias) => ($node = ib_node_by_alias($alias)) ? ['target_id' => $node->id()] : NULL;

$sections = [
  ['hero', [
    'hero_variant' => 'image_right',
    'eyebrow' => 'Home services',
    'heading' => 'Get paid before you leave the driveway.',
    'accent_word' => 'before',
    'intro' => 'Invoices by text, a reader in your pocket and payments that sync with your scheduling and accounting tools. One local team for your setup and support.',
    'link' => ib_link('/get-a-quote', 'Get a home services quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
    'checklist' => ['Invoices by text', 'A reader in your pocket', 'Syncs with your accounting'],
    'media_label' => 'a technician taking a card payment at a customer\'s front door',
    'show_breadcrumb' => 1,
  ]],
  ['feature_grid', [
    'eyebrow' => 'Sound familiar?',
    'heading' => 'What home service owners tell us first',
    'items' => [
      $card('clock', '“I chase invoices for weeks.”', 'Text an invoice with a payment link from the job site, and customers pay from their phone.'),
      $card('receipt', '“Checks bounce or go missing.”', 'Take a card on the spot, or ACH for bigger jobs, so the money is on its way before you leave.'),
      $card('smartphone', '“My techs can\'t take payment on site.”', 'A pocket reader pairs with each tech\'s phone, so anyone on the crew can close out a job.'),
      $card('file-text', '“Bookkeeping eats my Sunday.”', 'Payments sync to your accounting software, so invoices are marked paid without retyping.'),
    ],
  ]],
  ['feature_grid', [
    'eyebrow' => 'Ways to get paid',
    'heading' => 'On site, by text and on a schedule',
    'intro' => 'Most crews use all three. They run on one merchant account, so every payment lands in the same reports.',
    'items' => [
      $setup('On site', 'A pocket reader that pairs with a phone for chip, tap and swipe at the door or in the driveway.', 'Good for: repairs, installs, service calls', 'a technician with a card reader and phone in a customer\'s kitchen', 'See mobile readers', $catalog('Mobile reader')),
      $setup('By text', 'Send an invoice or a payment link by text or email, for deposits, estimates and jobs finished after hours.', 'Good for: deposits, larger jobs, follow-ups', 'a homeowner paying an invoice on her phone', 'See online payments', '/solutions/online'),
      $setup('On a schedule', 'Service plans that charge on a set day, for lawn care, pest control, pool and cleaning routes.', 'Good for: recurring routes, maintenance plans', 'a lawn care crew finishing a yard', 'See recurring billing', '/solutions/recurring-billing'),
    ],
  ]],
  ['bundle', [
    'eyebrow' => 'Recommended setup',
    'heading' => 'A setup for a three-truck crew',
    'intro' => 'A starting point we tune to your crew, your jobs and your volume. Swap any piece for something that fits better.',
    'anchor' => 'setup',
    'items' => array_values(array_filter([
      ($go = $node_ref('/hardware/clover-go')) ? ['_type' => 'bundle_item', 'label' => 'In each truck', 'reference' => $go, 'note' => 'One reader per tech, paired with their phone', 'quantity' => 3] : NULL,
      ($online = $solution('/solutions/online')) ? ['_type' => 'bundle_item', 'label' => 'Invoices', 'reference' => $online, 'note' => 'Payment links and invoices by text or email', 'quantity' => 1] : NULL,
      ($integrations = $solution('/solutions/integrations')) ? ['_type' => 'bundle_item', 'label' => 'Bookkeeping', 'reference' => $integrations, 'note' => 'Payments synced to your accounting software', 'quantity' => 1] : NULL,
    ])),
    'addons' => ['ACH payments', 'service plans'],
    'note' => 'Processing, gateway and any app fees are quoted separately.',
    'link' => ib_link('/get-a-quote', 'Quote this setup'),
  ]],
  ['media_split', [
    'eyebrow' => 'On the job',
    'heading' => 'Paid before the van pulls away',
    'body' => 'The best time to get paid is while the customer is happy with the work. Every piece of the setup is chosen to close the job on the spot.',
    'checklist' => [
      'Text an invoice from the driveway and get paid by phone',
      'Take a deposit by payment link before a big job starts',
      'Save a card on file, with the customer\'s permission, for repeat visits',
      'Every payment lands in the same reports, by tech and by job',
    ],
    'link' => ib_link('/solutions/integrations', 'See integrations'),
    'media_label' => 'a technician sending an invoice from the van',
  ]],
  ['card_grid', [
    'eyebrow' => 'Hardware',
    'heading' => 'Devices crews choose most',
    'intro' => 'Every device ships programmed to your account and ready for the first job.',
    'link' => ib_link('/hardware', 'See all hardware'),
    'source' => 'manual',
    'devices' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/hardware/' . $slug), ['clover-go', 'swipesimple-b350', 'clover-flex', 'pax-a920-pro']))),
  ]],
  ['faq', [
    'heading' => 'Home services questions',
    'layout' => 'centered',
    'items' => [
      $faq('Can customers pay an invoice by text?', 'Yes. Send an invoice or payment link by text or email, and the customer pays by card from their phone. You see it in your reports as soon as it goes through.'),
      $faq('Can I take a deposit before a job?', 'Yes. Send a payment link for the deposit when the customer accepts your estimate, then invoice the balance when the job is done.'),
      $faq('Can I accept ACH payments?', 'Yes. The virtual terminal takes card and ACH payments, which can cost less for large jobs. Ask us what fits your business.'),
      $faq('Will payments sync with my accounting software?', 'They can. Several gateways and partner apps sync payments to QuickBooks and other accounting tools. Tell us what you use and we\'ll recommend the simplest route.'),
      $faq('Can each tech have their own reader?', 'Yes. Each reader pairs with a tech\'s phone, and every payment runs through the same merchant account and reports.'),
    ],
  ]],
  ['related_resources', [
    'heading' => 'Keep reading',
    'resources' => array_values(array_filter(array_map(fn(string $slug) => $node_ref('/resources/' . $slug), ['payment-links-vs-online-store', 'funding-times-explained', 'read-a-processing-statement']))),
  ]],
  ['cta_band', [
    'heading' => 'Get paid on the spot, every job.',
    'accent_word' => 'spot',
    'body' => 'Tell us about your crew, your jobs and the tools you use. A local specialist replies within one business day.',
    'link' => ib_link('/get-a-quote', 'Get a home services quote'),
    'link_secondary' => ib_link($path . '#setup', 'See a sample setup'),
  ]],
];

$node = ib_node_by_alias($path);
if (!$node) {
  print "No home services page yet; run scripts/content/40-solutions-industries.php first.\n";
  return;
}
$existing = $node->get('field_sections')->referencedEntities();
$bundles = array_map(fn($section) => $section->bundle(), $existing);
if ($bundles === ['hero', 'callout', 'cta_band'] && $existing[1]->get('field_title')->value === 'More detail is on the way') {
  $node->set('field_sections', array_map(fn(array $section) => ib_paragraph($section[0], $section[1]), $sections));
  if ($node->hasField('field_meta_description')) {
    $node->set('field_meta_description', 'Card payments for Austin home service businesses: invoices by text, pocket readers for every tech, deposits and accounting sync.');
  }
  $node->save();
  foreach ($existing as $section) {
    $section->delete();
  }
  print "Home services page rebuilt from its short version.\n";
}
else {
  print "Home services page already built; left as it is.\n";
}
