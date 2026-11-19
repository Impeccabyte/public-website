<?php

namespace Drupal\impeccabyte_site\Plugin\WebformHandler;

use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * A placeholder for sending leads to Apollo.
 *
 * The Apollo account does not exist yet, so this handler sends nothing. It
 * marks where the call goes: read the API key from settings.local.php or the
 * environment, post the contact, and log a failure without blocking the
 * visitor, the same way the Intercom handler does.
 *
 * @WebformHandler(
 *   id = "impeccabyte_apollo_lead",
 *   label = @Translation("Apollo lead (not built yet)"),
 *   category = @Translation("Leads"),
 *   description = @Translation("Placeholder. Sends nothing until the Apollo account exists and this handler is finished."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_OPTIONAL,
 * )
 */
class ApolloLeadHandler extends WebformHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    return ['#markup' => $this->t('Not built yet. Nothing is sent to Apollo.')];
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    // Intentionally empty until the Apollo account exists.
  }

}
