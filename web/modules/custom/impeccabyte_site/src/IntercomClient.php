<?php

namespace Drupal\impeccabyte_site;

use Drupal\Core\Site\Settings;
use GuzzleHttp\ClientInterface;

/**
 * A small Intercom client for sending website leads.
 *
 * The access token is read from settings.local.php
 * ($settings['impeccabyte_intercom']['access_token']) or from the
 * INTERCOM_ACCESS_TOKEN environment variable. It is never stored in config.
 */
class IntercomClient {

  public const API_BASE = 'https://api.intercom.io';

  public const API_VERSION = '2.11';

  public function __construct(protected ClientInterface $httpClient) {}

  /**
   * The access token, or an empty string when none is set.
   */
  protected function token(): string {
    $token = (string) (Settings::get('impeccabyte_intercom')['access_token'] ?? '');
    return $token !== '' ? $token : (string) (getenv('INTERCOM_ACCESS_TOKEN') ?: '');
  }

  /**
   * Whether a token is available.
   */
  public function isConfigured(): bool {
    return $this->token() !== '';
  }

  /**
   * Creates or updates a lead, tags it and leaves a note with the details.
   *
   * @param array $person
   *   Keys: email (required), name, phone.
   * @param string[] $tags
   *   Tag names. Missing tags are created.
   * @param string $note
   *   HTML for a note on the contact, or an empty string.
   *
   * @return string
   *   The Intercom contact id.
   */
  public function sendLead(array $person, array $tags, string $note = ''): string {
    $details = array_filter(['name' => $person['name'] ?? '', 'phone' => $person['phone'] ?? '']);
    $found = $this->request('POST', '/contacts/search', [
      'query' => ['field' => 'email', 'operator' => '=', 'value' => $person['email']],
    ]);
    $contact = $found['data'][0] ?? NULL;
    if ($contact) {
      // Fill in what Intercom does not have yet; never overwrite.
      $missing = array_filter($details, fn($value, $key) => empty($contact[$key]), ARRAY_FILTER_USE_BOTH);
      if ($missing) {
        $this->request('PUT', '/contacts/' . rawurlencode($contact['id']), $missing);
      }
    }
    else {
      $contact = $this->request('POST', '/contacts', ['role' => 'lead', 'email' => $person['email']] + $details);
    }
    foreach (array_unique(array_filter($tags)) as $name) {
      $tag = $this->request('POST', '/tags', ['name' => $name]);
      $this->request('POST', '/contacts/' . rawurlencode($contact['id']) . '/tags', ['id' => $tag['id']]);
    }
    if ($note !== '') {
      $this->request('POST', '/contacts/' . rawurlencode($contact['id']) . '/notes', ['body' => $note]);
    }
    return (string) $contact['id'];
  }

  /**
   * Sends one request and decodes the JSON reply.
   */
  protected function request(string $method, string $path, array $body = []): array {
    $response = $this->httpClient->request($method, self::API_BASE . $path, [
      'headers' => [
        'Authorization' => 'Bearer ' . $this->token(),
        'Accept' => 'application/json',
        'Intercom-Version' => self::API_VERSION,
      ],
      'json' => $body,
      'timeout' => 10,
      'connect_timeout' => 5,
    ]);
    return json_decode((string) $response->getBody(), TRUE) ?: [];
  }

}
