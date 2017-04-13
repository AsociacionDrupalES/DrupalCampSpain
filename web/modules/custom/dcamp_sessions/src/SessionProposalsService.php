<?php

namespace Drupal\dcamp_sessions;

use Drupal\dcamp_sessions\Entity\Session;
use Google_Client;
use Google_Service_Sheets;

class SessionProposalsService {

  /**
   * Returns the spreadsheet values.
   *
   * @return \Drupal\dcamp_sessions\Entity\Session[]
   *   The array of session proposals.
   *
   * @throws \RuntimeException
   *   If there is no credentials file to authenticate against Google.
   */
  public function getProposals() {
    $config = \Drupal::config('dcamp_sessions.settings');
    /** @var \Drupal\Core\Path\AliasStorage $aliasStorage */
    $aliasStorage = \Drupal::service('path.alias_storage');
    $pathAutoAliasCleaner = \Drupal::service('pathauto.alias_cleaner');
    $sessions = [];

    // First check if we are in developer mode.
    if ($config->get('debugging')) {
      $path = \Drupal::service('module_handler')->getModule('dcamp_sessions')->getPath();
      $sessions_raw = file_get_contents($path . '/fixtures/sessions.json');
      $sessions_json = json_decode($sessions_raw);
    }
    else {
      if (empty($config->get('service_account_file'))) {
        throw new \RuntimeException('The path of the service account file has not been set.');
      }
      if (empty($config->get('spreadsheet_id'))) {
        throw new \RuntimeException('The identifier of the spreadsheet has not been set.');
      }
      if (empty($config->get('spreadsheet_range'))) {
        throw new \RuntimeException('The range of the spreadsheet has not been set.');
      }
      putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $config->get('service_account_file'));
      $client = new Google_Client();
      $client->useApplicationDefaultCredentials();
      $client->addScope(Google_Service_Sheets::SPREADSHEETS_READONLY);
      $service = new Google_Service_Sheets($client);

      $result = $service->spreadsheets_values->get($config->get('spreadsheet_id'), $config->get('spreadsheet_range'));

      // Skip the first row from the spreadsheet values as it contains heading titles.
      $sessions_json = array_slice($result->getValues(), 1);
    }

    // Turn raw session proposals into Session objects.
    foreach ($sessions_json as $key => $session_json) {
      $session = new Session($session_json);

      // Check if we need to create an alias.
      $alias = '/sessions/' . $pathAutoAliasCleaner->cleanString($session->getTitle() . '-' . $session->getTwitter());
      if (!$aliasStorage->load(['alias' => $alias])) {
        $aliasStorage->save('/sessions/' . $key, $alias);
      }
      $session->setUrl($alias);
      $sessions[] = $session;
    }

    return $sessions;
  }


}