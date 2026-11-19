<?php

/**
 * @file
 * Webforms: statement review. Uploads go to the private file system and
 * submissions stay in the database.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/70-forms.php
 * then export config.
 */

use Drupal\webform\Entity\Webform;

require_once __DIR__ . '/_helpers.php';

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

print "Forms are in place.\n";
