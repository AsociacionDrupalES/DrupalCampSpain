<?php

namespace Drupal\dcamp_sessions\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\dcamp_sessions\Entity\Session;
use Google_Client;
use Google_Service_Sheets;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DcampSessionsController extends ControllerBase {

  /**
   * The amount of seconds to cache session listings.
   *
   * @var int
   */
  protected $maxAge = 120;

  /**
   * Lists proposed sessions
   *
   * @return mixed
   *   JsonResponse when requested via API request. A render array
   *   otherwise.
   */
  public function listSessions() {
    $sessions = $this->getProposals();

    // Check if this is an API request.
    if (\Drupal::request()->query->get('_format') == 'json') {
      // Prepare and send response.
      $headers = [
        'max-age' => $this->maxAge,
      ];
      return new JsonResponse($sessions, Response::HTTP_OK, $headers);
    }

    $list_items = [];
    foreach ($sessions as $session) {
      $list_items[] = [
        '#markup' => '<h2 class="session__title--list"><a href="' . $session->getUrl() . '">' . Xss::filter($session->getTitle()) . '</a></h2><div class="session__author">'. Xss::filter($session->getName()) . '</div>',
      ];
    }

    return [
      '#theme' => 'proposed_sessions',
      '#items' => $list_items,
      '#cache' => [
        'max-age' => $this->maxAge,
      ],
    ];
  }

  /**
   * View session details
   *
   * @param int $submission_id
   *   The identifier of the submission id, which maps to the row
   *   in the spreadsheet.
   *
   * @return mixed
   *   JsonResponse when requested via API request. A render array
   *   otherwise.
   */
  public function view($submission_id) {
    $submission_id = (int) $submission_id;
    $sessions = $this->getProposals();
    if (empty($sessions[$submission_id])) {
      throw new BadRequestHttpException(t('Invalid submission id. https://media4.giphy.com/media/uOAXDA7ZeJJzW/giphy.gif'));
    }

    // Extract session details.
    $session = $sessions[$submission_id];

    // Check if this is an API request.
    if (\Drupal::request()->query->get('_format') == 'json') {
      // Prepare and send response.
      $headers = [
        'max-age' => $this->maxAge,
      ];
      return new JsonResponse($session, Response::HTTP_OK, $headers);
    }

    $build = [
      '#theme' => 'proposed_session',
      '#session' => $session,
      '#cache' => [
        'max-age' => $this->maxAge,
      ],
    ];

    return $build;
  }

  /**
   * Returns the spreadsheet values.
   *
   * @return \Drupal\dcamp_sessions\Entity\Session[]
   *   The array of session proposals.
   * @throws \RuntimeException
   *   If there is no credentials file to authenticate against Google.
   */
  protected function getProposals() {
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
      $alias = '/sessions/' . $pathAutoAliasCleaner->cleanString($session->getTitle());
      if (!$aliasStorage->load(['alias' => $alias])) {
        $aliasStorage->save('/sessions/proposed/' . $key, $alias);
      }
      $session->setUrl($alias);
      $sessions[] = $session;
    }

    return $sessions;
  }

}
