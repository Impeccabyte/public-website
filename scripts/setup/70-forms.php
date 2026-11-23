<?php

/**
 * @file
 * Webforms: get a quote and statement review, and the lead handlers on every
 * form. Uploads go to the private file system and submissions stay in the
 * database.
 *
 * Each form also gets an Intercom handler and an Apollo placeholder. The
 * Intercom handler is switched on only when a token is present in
 * settings.local.php or the environment when this script runs; the token
 * itself never enters config. The Apollo handler stays off: that account
 * does not exist yet.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/70-forms.php
 * then export config.
 */

use Drupal\webform\Entity\Webform;

require_once __DIR__ . '/_helpers.php';

// Errors show beside the field they belong to, not only at the top.
\Drupal::service('module_installer')->install(['inline_form_errors']);

if (!Webform::load('statement_review')) {
  $elements = <<<YAML
details:
  '#type': container
  '#attributes':
    class:
      - form-grid
  name:
    '#type': textfield
    '#title': 'Your name'
    '#placeholder': 'Jordan Reyes'
    '#required': true
    '#attributes':
      autocomplete: name
  business_name:
    '#type': textfield
    '#title': 'Business name'
    '#placeholder': 'Reyes Barbershop'
    '#required': true
    '#attributes':
      autocomplete: organization
  email:
    '#type': email
    '#title': Email
    '#placeholder': you@yourbusiness.com
    '#required': true
    '#attributes':
      autocomplete: email
  phone:
    '#type': tel
    '#title': Phone
    '#description': Optional
    '#description_display': before
    '#placeholder': '(512) 555-0123'
    '#attributes':
      autocomplete: tel
payment_method:
  '#type': select
  '#title': 'How do you take payments today?'
  '#options':
    countertop: 'Countertop terminal'
    pos: 'Full POS system'
    mobile: 'Mobile reader or phone'
    online: 'Online only'
    mix: 'A mix of these'
    none: 'I am not taking cards yet'
  '#empty_option': 'Choose one'
statement:
  '#type': managed_file
  '#title': 'Your statement'
  '#description': 'PDF, JPG or PNG, up to 10 MB'
  '#description_display': before
  '#required': true
  '#uri_scheme': private
  '#max_filesize': 10
  '#file_help': none
  '#file_extensions': 'pdf jpg jpeg png'
  '#file_name': 'statement-[webform_submission:sid]'
  '#sanitize': true
  '#button': true
  '#button__title': 'Choose a file'
consent:
  '#type': checkbox
  '#title': "It's okay to contact me about this review"
  '#description': "We'll only use your details to send your comparison."
  '#default_value': true
  '#required': true
  '#required_error': 'Please check the box so we can send your comparison.'
actions:
  '#type': webform_actions
  '#title': 'Submit button'
  '#submit__label': 'Send my statement'
  '#submit__attributes':
    class:
      - button--lg
      - button--block
YAML;
  $webform = Webform::create(['id' => 'statement_review', 'title' => 'Statement review', 'description' => 'Upload a processing statement for a free review.', 'category' => 'Leads', 'elements' => $elements]);
  $webform->setSettings([
    'ajax' => FALSE,
    'page' => TRUE,
    'page_submit_path' => '/statement-review',
    'form_title' => 'source_entity_webform',
    'confirmation_type' => 'inline',
    'confirmation_message' => '<p><strong>Thanks. We have your statement.</strong></p><p>A local specialist will send your written comparison, usually within one business day.</p>',
    'results_disabled' => FALSE,
    'form_novalidate' => FALSE,
  ]);
  $webform->save();
}

if (!Webform::load('get_a_quote')) {
  // Merchant services only at launch, so business banking is not an interest.
  $elements = <<<YAML
business:
  '#type': webform_wizard_page
  '#title': 'Business info'
  business_heading:
    '#type': webform_markup
    '#markup': '<h2 class="quote-step__title">Your business</h2>'
  business_fields:
    '#type': container
    '#attributes':
      class:
        - form-grid
    business_name:
      '#type': textfield
      '#title': 'Business name'
      '#placeholder': 'Reyes Barbershop'
      '#required': true
      '#attributes':
        autocomplete: organization
    business_type:
      '#type': select
      '#title': 'Type of business'
      '#empty_option': 'Choose one'
      '#options':
        restaurant: 'Restaurants & Bars'
        food_truck: 'Food Trucks'
        retail: Retail
        salon: 'Salons & Spas'
        home_services: 'Home Services'
        healthcare: 'Healthcare & Dental'
        auto: 'Auto Repair'
        professional: 'Professional Services'
        nonprofit: Nonprofits
        other: 'Something else'
    time_open:
      '#type': select
      '#title': 'How long have you been open?'
      '#empty_option': 'Choose one'
      '#options':
        not_open: 'Not open yet'
        under_1: 'Less than a year'
        1_to_3: '1 to 3 years'
        over_3: 'More than 3 years'
    zip:
      '#type': textfield
      '#title': 'ZIP code'
      '#placeholder': '78704'
      '#description': 'Helps us match you with a local specialist'
      '#description_display': before
      '#maxlength': 10
      '#pattern': '[0-9]{5}(-[0-9]{4})?'
      '#pattern_error': 'Enter a 5-digit ZIP code, for example 78704.'
      '#attributes':
        autocomplete: postal-code
        inputmode: numeric
    monthly_volume:
      '#type': select
      '#title': 'Monthly card sales, roughly'
      '#empty_option': 'Choose one'
      '#options':
        none: 'Not taking cards yet'
        under_10k: 'Under $10,000'
        10k_to_50k: '$10,000 to $50,000'
        50k_to_150k: '$50,000 to $150,000'
        over_150k: 'Over $150,000'
payments:
  '#type': webform_wizard_page
  '#title': 'How you take payments'
  payments_heading:
    '#type': webform_markup
    '#markup': '<h2 class="quote-step__title">How you take payments</h2>'
  channels:
    '#type': checkboxes
    '#title': 'Where do you take payments?'
    '#description': 'Choose all that apply.'
    '#description_display': before
    '#options':
      store: 'In a store or counter -- Customers pay in person'
      online: 'Online -- Website, links or invoices'
      go: 'On the go -- Markets, events, job sites'
      recurring: 'Recurring billing -- Memberships or retainers'
    '#options_description_display': description
    '#wrapper_attributes':
      class:
        - option-cards
  current_processor:
    '#type': select
    '#title': 'Who processes your cards today?'
    '#empty_option': 'Choose one'
    '#options':
      square: Square
      stripe: Stripe
      clover_bank: 'Clover through my bank'
      toast: Toast
      other: 'Another provider'
      none: 'Not taking cards yet'
  interests:
    '#type': checkboxes
    '#title': 'Also interested in'
    '#options':
      dual_pricing: 'Dual pricing'
      hardware: 'New hardware'
      chargeback: 'Chargeback protection'
    '#default_value':
      - dual_pricing
    '#wrapper_attributes':
      class:
        - inline-checks
contact:
  '#type': webform_wizard_page
  '#title': Contact
  contact_heading:
    '#type': webform_markup
    '#markup': '<h2 class="quote-step__title">How to reach you</h2>'
  contact_fields:
    '#type': container
    '#attributes':
      class:
        - form-grid
    first_name:
      '#type': textfield
      '#title': 'First name'
      '#placeholder': Jordan
      '#required': true
      '#attributes':
        autocomplete: given-name
    last_name:
      '#type': textfield
      '#title': 'Last name'
      '#placeholder': Reyes
      '#required': true
      '#attributes':
        autocomplete: family-name
    email:
      '#type': email
      '#title': Email
      '#placeholder': you@yourbusiness.com
      '#required': true
      '#attributes':
        autocomplete: email
    mobile_phone:
      '#type': tel
      '#title': 'Mobile phone'
      '#placeholder': '(512) 555-0123'
      '#attributes':
        autocomplete: tel
  contact_method:
    '#type': radios
    '#title': 'Best way to reach you'
    '#options':
      text: Text
      call: Call
      email: Email
    '#default_value': text
    '#wrapper_attributes':
      class:
        - pill-radios
  consent:
    '#type': checkbox
    '#title': "It's okay to contact me about my quote"
    '#description': 'We never sell your information. Message and data rates may apply for texts.'
    '#required': true
    '#required_error': 'Please check the box so we can send your quote.'
device:
  '#type': hidden
  '#title': 'Device asked about'
  '#prepopulate': true
actions:
  '#type': webform_actions
  '#title': 'Submit button'
  '#submit__label': 'Send my quote request'
  '#submit__attributes':
    class:
      - button--lg
  '#wizard_prev__attributes':
    class:
      - button--lg
  '#wizard_next__attributes':
    class:
      - button--lg
YAML;
  $webform = Webform::create(['id' => 'get_a_quote', 'title' => 'Get a quote', 'description' => 'The three-step quote request.', 'category' => 'Leads', 'elements' => $elements]);
  $webform->setSettings([
    'ajax' => FALSE,
    'page' => TRUE,
    'page_submit_path' => '/get-a-quote',
    'page_confirm_path' => '',
    'wizard_progress_bar' => TRUE,
    'wizard_progress_pages' => TRUE,
    'wizard_progress_percentage' => FALSE,
    'wizard_confirmation' => FALSE,
    'wizard_prev_button_label' => 'Back',
    'wizard_next_button_label' => 'Continue',
    'wizard_page_title_tag' => 'h2',
    'confirmation_type' => 'inline',
    'confirmation_message' => '<h2 class="quote-step__title">Thanks, [webform_submission:values:first_name]. We\'re on it.</h2><p>A local specialist will reach out within one business day. Want a sharper quote? Send us a recent statement.</p><p><a class="button button--secondary" href="/pricing#statement">Upload a statement</a></p>',
    'form_novalidate' => FALSE,
  ]);
  $webform->save();
}

// Specialty pre-qualification, embedded on each specialty industry page. The
// page it sits on picks the industry; the Intercom handler tags by it.
if (!Webform::load('specialty_prequal')) {
  $elements = <<<YAML
vertical:
  '#type': select
  '#title': Industry
  '#required': true
  '#empty_option': 'Choose one'
  '#options':
    'Hemp & CBD': 'Hemp & CBD'
    'Smoke & Vape': 'Smoke & Vape'
    Kratom: Kratom
    Firearms: Firearms
    'Adult Content': 'Adult Content'
    Dating: Dating
    Gaming: Gaming
    Collections: Collections
    'Credit Repair': 'Credit Repair'
    Lending: Lending
    'Nutraceuticals & Subscriptions': 'Nutraceuticals & Subscriptions'
    'Online Pharmacy & Telehealth': 'Online Pharmacy & Telehealth'
website_urls:
  '#type': textarea
  '#title': 'Website addresses'
  '#description': 'List every site you sell on, one per line.'
  '#description_display': before
  '#rows': 3
  '#required': true
business_numbers:
  '#type': container
  '#attributes':
    class:
      - form-grid
  monthly_volume:
    '#type': select
    '#title': 'Monthly card sales, roughly'
    '#empty_option': 'Choose one'
    '#options':
      none: 'Not taking cards yet'
      under_10k: 'Under $10,000'
      10k_to_50k: '$10,000 to $50,000'
      50k_to_150k: '$50,000 to $150,000'
      over_150k: 'Over $150,000'
  average_ticket:
    '#type': textfield
    '#title': 'Average sale'
    '#placeholder': '$60'
    '#maxlength': 20
chargeback_history:
  '#type': select
  '#title': 'Chargebacks in the last 12 months'
  '#empty_option': 'Choose one'
  '#options':
    none: 'None'
    few: 'A few'
    monthly: 'Some most months'
    unsure: 'Not sure'
    new: 'New business, no history'
licenses:
  '#type': textarea
  '#title': 'Licenses and permits you hold'
  '#description': 'For example state licenses, permits or registrations. Leave blank if none apply.'
  '#description_display': before
  '#rows': 3
contact_fields:
  '#type': container
  '#attributes':
    class:
      - form-grid
  name:
    '#type': textfield
    '#title': 'Your name'
    '#required': true
    '#attributes':
      autocomplete: name
  business_name:
    '#type': textfield
    '#title': 'Business name'
    '#required': true
    '#attributes':
      autocomplete: organization
  email:
    '#type': email
    '#title': Email
    '#required': true
    '#attributes':
      autocomplete: email
  phone:
    '#type': tel
    '#title': Phone
    '#description': Optional
    '#description_display': before
    '#attributes':
      autocomplete: tel
consent:
  '#type': checkbox
  '#title': "It's okay to contact me about this request"
  '#description': 'We review your website before we send an application to underwriting. We never sell your information.'
  '#required': true
  '#required_error': 'Please check the box so we can reply.'
actions:
  '#type': webform_actions
  '#title': 'Submit button'
  '#submit__label': 'Send my details'
  '#submit__attributes':
    class:
      - button--lg
      - button--block
YAML;
  $webform = Webform::create(['id' => 'specialty_prequal', 'title' => 'Specialty pre-qualification', 'description' => 'First questions for a specialty industry, embedded on each specialty page.', 'category' => 'Leads', 'elements' => $elements]);
  $webform->setSettings([
    'ajax' => FALSE,
    'page' => TRUE,
    'page_submit_path' => '/industries/specialty/pre-qualify',
    'form_title' => 'source_entity_webform',
    'confirmation_type' => 'inline',
    'confirmation_message' => '<p><strong>Thanks. We have your details.</strong></p><p>A local specialist will review your website and reply within one business day with the paperwork underwriting will ask for. Sending this form is not an application and not an approval.</p>',
    'form_novalidate' => FALSE,
  ]);
  $webform->save();
}

// Lead handlers. A handler that is already on a form is left as it is.
$has_token = \Drupal::service('impeccabyte_site.intercom')->isConfigured();
$handlers = \Drupal::service('plugin.manager.webform.handler');
$forms = [
  'get_a_quote' => ['Website lead', 'Quote request'],
  'statement_review' => ['Website lead', 'Statement review'],
  'newsletter' => ['Newsletter'],
  'specialty_prequal' => ['Website lead', 'Specialty'],
];
foreach ($forms as $id => $tags) {
  $webform = Webform::load($id);
  if (!$webform) {
    continue;
  }
  $existing = $webform->getHandlers();
  $changed = FALSE;
  if (!$existing->has('intercom')) {
    $webform->addWebformHandler($handlers->createInstance('impeccabyte_intercom_lead', [
      'id' => 'impeccabyte_intercom_lead',
      'handler_id' => 'intercom',
      'label' => 'Intercom lead',
      'notes' => 'On only when a token is set in settings.local.php or the environment.',
      'status' => $has_token,
      'weight' => 0,
      // Specialty leads are also tagged with the industry they picked.
      'settings' => ['tags' => $tags, 'tag_elements' => $id === 'specialty_prequal' ? ['vertical'] : []],
    ]));
    $changed = TRUE;
  }
  if (!$existing->has('apollo')) {
    $webform->addWebformHandler($handlers->createInstance('impeccabyte_apollo_lead', [
      'id' => 'impeccabyte_apollo_lead',
      'handler_id' => 'apollo',
      'label' => 'Apollo lead (not built yet)',
      'notes' => 'Placeholder. The Apollo account does not exist yet.',
      'status' => FALSE,
      'weight' => 1,
      'settings' => [],
    ]));
    $changed = TRUE;
  }
  if ($changed) {
    $webform->save();
  }
}

print "Forms are in place. Intercom handler: " . ($has_token ? "on" : "off, no token found") . ". Apollo handler: off.\n";
