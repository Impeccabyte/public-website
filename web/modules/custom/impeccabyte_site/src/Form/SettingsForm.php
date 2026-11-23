<?php

namespace Drupal\impeccabyte_site\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Site-wide settings for the Impeccabyte marketing site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'impeccabyte_site_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['impeccabyte_site.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('impeccabyte_site.settings');
    $form['show_prices'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show prices'),
      '#description' => $this->t('While this is off, every price on the site (device cards, device pages, pricing cards, the comparison table and setup estimates) shows the text below with a link to the quote form. Turn it on once pricing is final.'),
      '#default_value' => $config->get('show_prices'),
    ];
    $form['price_placeholder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text shown in place of a price'),
      '#default_value' => $config->get('price_placeholder'),
      '#required' => TRUE,
    ];
    $form['quote_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Path of the quote form'),
      '#default_value' => $config->get('quote_path'),
      '#required' => TRUE,
    ];
    $form['contact'] = [
      '#type' => 'details',
      '#title' => $this->t('Contact details'),
      '#description' => $this->t('Shown in the header, the menus and the footer.'),
      '#open' => TRUE,
    ];
    $contact = [
      'phone' => $this->t('Phone number'),
      'hours' => $this->t('Phone hours'),
      'email' => $this->t('Contact email'),
      'address_line1' => $this->t('Street address'),
      'address_line2' => $this->t('City, state and ZIP'),
      'login_url' => $this->t('Merchant app address'),
    ];
    foreach ($contact as $key => $title) {
      $form['contact'][$key] = [
        '#type' => $key === 'email' ? 'email' : ($key === 'login_url' ? 'url' : 'textfield'),
        '#title' => $title,
        '#default_value' => $config->get($key),
      ];
    }
    $form['contact']['phone']['#description'] = $this->t('Leave empty to show no phone number. The contact email shows in its place.');
    $form['quote_form'] = [
      '#type' => 'details',
      '#title' => $this->t('Embedded forms'),
      '#description' => $this->t('Forms from the Impeccabyte app, shown in place of the Drupal forms.'),
      '#open' => TRUE,
    ];
    $form['quote_form']['forms_base_url'] = [
      '#type' => 'url',
      '#title' => $this->t('App address'),
      '#default_value' => $config->get('forms_base_url'),
    ];
    $form['quote_form']['quote_form_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Quote form ID'),
      '#description' => $this->t('Used on the Get a quote page and the statement review on Pricing. The data-ib-form value in the embed code.'),
      '#default_value' => $config->get('quote_form_id'),
    ];
    $form['quote_form']['specialty_form_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Specialty prequalification form ID'),
      '#description' => $this->t('Used on the specialty industry pages. The data-ib-form value in the embed code.'),
      '#default_value' => $config->get('specialty_form_id'),
    ];
    $form['service_area'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Service area'),
      '#description' => $this->t('Cities in Texas where specialists meet merchants, one per line. Search engines read this list from the home page, so keep it the same as the service area in the Google Business Profile.'),
      '#default_value' => implode("\n", $config->get('service_area') ?? []),
      '#rows' => 6,
    ];
    $form['disclosure'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Footer disclosure'),
      '#description' => $this->t('Shown on every page. The reseller agreement requires Maverick Payments to be named on all marketing, so have any change reviewed first.'),
      '#default_value' => $config->get('disclosure'),
      '#required' => TRUE,
      '#rows' => 3,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $config = $this->config('impeccabyte_site.settings')
      ->set('show_prices', (bool) $form_state->getValue('show_prices'))
      ->set('price_placeholder', trim($form_state->getValue('price_placeholder')))
      ->set('quote_path', trim($form_state->getValue('quote_path')));
    foreach (['phone', 'hours', 'email', 'address_line1', 'address_line2', 'login_url', 'disclosure'] as $key) {
      $config->set($key, trim((string) $form_state->getValue($key)));
    }
    $config->set('forms_base_url', rtrim(trim((string) $form_state->getValue('forms_base_url')), '/'));
    $config->set('quote_form_id', trim((string) $form_state->getValue('quote_form_id')));
    $config->set('specialty_form_id', trim((string) $form_state->getValue('specialty_form_id')));
    $cities = array_map('trim', preg_split('/\R/', (string) $form_state->getValue('service_area')));
    $config->set('service_area', array_values(array_unique(array_filter($cities, 'strlen'))));
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
