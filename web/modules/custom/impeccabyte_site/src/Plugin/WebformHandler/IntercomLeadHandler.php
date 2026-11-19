<?php

namespace Drupal\impeccabyte_site\Plugin\WebformHandler;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Sends a completed submission to Intercom as a lead.
 *
 * The submission is always kept in the Drupal database first. If Intercom is
 * not configured or does not answer, the visitor still sees the confirmation
 * and the failure is logged.
 *
 * @WebformHandler(
 *   id = "impeccabyte_intercom_lead",
 *   label = @Translation("Intercom lead"),
 *   category = @Translation("Leads"),
 *   description = @Translation("Creates or updates a lead in Intercom, tags it and adds the answers as a note. The access token is read from settings.local.php or the environment, never from configuration."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_OPTIONAL,
 * )
 */
class IntercomLeadHandler extends WebformHandlerBase {

  /**
   * The Intercom client.
   *
   * @var \Drupal\impeccabyte_site\IntercomClient
   */
  protected $intercom;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->intercom = $container->get('impeccabyte_site.intercom');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'tags' => ['Website lead'],
      'tag_elements' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    $configured = $this->intercom->isConfigured();
    return [
      '#markup' => $this->t('Tags: @tags. Token: @state.', [
        '@tags' => implode(', ', $this->configuration['tags']) ?: $this->t('none'),
        '@state' => $configured ? $this->t('found') : $this->t('not set, so nothing is sent'),
      ]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['tags'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tags'),
      '#description' => $this->t('Comma-separated Intercom tags added to every lead from this form.'),
      '#default_value' => implode(', ', $this->configuration['tags']),
    ];
    $form['tag_elements'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Elements to tag by'),
      '#description' => $this->t('Comma-separated element keys. The chosen option of each becomes a tag, for example the specialty industry.'),
      '#default_value' => implode(', ', $this->configuration['tag_elements']),
    ];
    return $this->setSettingsParents($form);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $split = fn($value) => array_values(array_filter(array_map('trim', explode(',', (string) $value))));
    $this->configuration['tags'] = $split($form_state->getValue('tags'));
    $this->configuration['tag_elements'] = $split($form_state->getValue('tag_elements'));
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    // Only a newly completed submission becomes a lead.
    if ($webform_submission->getState() !== WebformSubmissionInterface::STATE_COMPLETED || !$this->intercom->isConfigured()) {
      return;
    }
    $data = $webform_submission->getData();
    $email = trim((string) ($data['email'] ?? ''));
    if ($email === '') {
      return;
    }
    // A form that asks for consent to be contacted is only sent with it.
    if (array_key_exists('consent', $data) && empty($data['consent'])) {
      return;
    }

    $name = trim((string) ($data['name'] ?? trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''))));
    $phone = trim((string) ($data['mobile_phone'] ?? $data['phone'] ?? ''));
    $elements = $this->getWebform()->getElementsInitializedFlattenedAndHasValue();
    $tags = $this->configuration['tags'];
    $lines = [];
    foreach ($elements as $key => $element) {
      $value = $data[$key] ?? NULL;
      if ($value === NULL || $value === '' || $value === []) {
        continue;
      }
      // Uploaded files stay in Drupal; the note only says one was sent.
      if (in_array($element['#type'] ?? '', ['managed_file', 'webform_document_file', 'webform_image_file'], TRUE)) {
        $value = (string) $this->t('Uploaded. Open the submission in Drupal to view it.');
      }
      else {
        $options = $element['#options'] ?? [];
        $labels = array_map(fn($item) => preg_replace('/ -- .*$/', '', (string) ($options[$item] ?? $item)), (array) $value);
        if (in_array($key, $this->configuration['tag_elements'], TRUE)) {
          $tags = array_merge($tags, $labels);
        }
        $value = ($element['#type'] ?? '') === 'checkbox' ? (string) $this->t('Yes') : implode(', ', $labels);
      }
      $lines[] = '<strong>' . Html::escape(strip_tags((string) ($element['#title'] ?? $key))) . ':</strong> ' . Html::escape($value);
    }
    $note = '<p><strong>' . Html::escape($this->getWebform()->label()) . '</strong> (' . Html::escape((string) $this->t('website form, submission @sid', ['@sid' => $webform_submission->id()])) . ')</p><p>' . implode('<br>', $lines) . '</p>';

    try {
      $this->intercom->sendLead(['email' => $email, 'name' => $name, 'phone' => $phone], $tags, $note);
    }
    catch (\Throwable $e) {
      // The lead is safe in Drupal; record why Intercom did not get it.
      $this->getLogger('impeccabyte_site')->error('Intercom did not accept submission @sid of @form: @message', [
        '@sid' => $webform_submission->id(),
        '@form' => $this->getWebform()->id(),
        '@message' => $e->getMessage(),
      ]);
    }
  }

}
