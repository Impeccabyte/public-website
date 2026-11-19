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
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('impeccabyte_site.settings')
      ->set('show_prices', (bool) $form_state->getValue('show_prices'))
      ->set('price_placeholder', trim($form_state->getValue('price_placeholder')))
      ->set('quote_path', trim($form_state->getValue('quote_path')))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
