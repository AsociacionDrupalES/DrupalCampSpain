<?php

namespace Drupal\dcamp_sessions\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
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
  protected $maxAge;

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
    foreach ($sessions as $submission_id => $session) {
      $list_items[] = [
        '#markup' => '<h2 class="teaser-list__title"><a href="' . $session[13] . '">' . $session[10] . '</a></h2><div class="teaser-list__subtitle">'. $session[2] . '</div>',
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
      '#type' => 'table',
      '#caption' => $session[10],
      '#prefix' => '<a href="/sessions/proposed">' . $this->t('List proposed sessions') . '</a>',
      '#header' => ['Field', 'Value'],
      '#cache' => [
        'max-age' => $this->maxAge,
      ],
    ];

    $build[] = [
      'name' => ['#markup' => 'Name'],
      'value' => ['#markup' => !empty($session[2]) ? $session[2] : ''],
    ];

    $build[] = [
      'name' => ['#markup' => 'Drupal.org'],
      'value' => ['#markup' => !empty($session[3]) ? $session[3] : ''],
    ];

    $build[] = [
      'name' => ['#markup' => 'Photo'],
      'value' => ['#markup' => '<img src="' . (!empty($session[4]) ? $session[4] : '') . '"/>'],
    ];

    $build[] = [
      'name' => ['#markup' => 'Twitter'],
      'value' => ['#markup' => !empty($session[5]) ? $session[5] : ''],
    ];

    $build[] = [
      'name' => ['#markup' => 'Bio'],
      'value' => ['#markup' => !empty($session[6]) ? $session[6] : ''],
    ];

    $build[] = [
      'name' => ['#markup' => 'Session type'],
      'value' => ['#markup' => !empty($session[7]) ? $session[7] : ''],
    ];

    $build[] = [
      'name' => ['#markup' => 'Session level'],
      'value' => ['#markup' => !empty($session[8]) ? $session[8] : ''],
    ];

    $build[] = [
      'name' => ['#markup' => 'Language'],
      'value' => ['#markup' => !empty($session[9]) ? $session[9] : ''],
    ];

    $build[] = [
      'name' => ['#markup' => 'Description'],
      'value' => ['#markup' => !empty($session[11]) ? $session[11] : ''],
    ];

    return $build;
  }

  /**
   * Returns the spreadsheet values.
   *
   * @return array
   *   The array of session proposals.
   * @throws \RuntimeException
   *   If there is no credentials file to authenticate against Google.
   */
  protected function getProposals() {
    $config = \Drupal::config('dcamp_sessions.settings');

    // First check if we are in developer mode.
    if ($config->get('debugging')) {
      $path = \Drupal::service('module_handler')->getModule('dcamp_sessions')->getPath();
      $sessions = file_get_contents($path . '/fixtures/sessions.json');
      return json_decode($sessions);
    }

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
    $sessions = array_slice($result->getValues(), 1);

    // Create aliases for session proposals.
    $this->createAliases($sessions);

    // Add URLs to session proposals.
    $sessions_with_urls = [];
    foreach ($sessions as $submission_id => $session) {
      $url = Url::fromRoute('dcamp_sessions.view', [
        'submission_id' => $submission_id,
      ]);
      if (count($session) == 12) {
        // Argh! This proposal does not have the last question filled up,
        // and Google prefers to remove the cell rather than sending and empty
        // cell.
        $session[] = '';
      }
      $session[] = $url->toString();
      $sessions_with_urls[] = $session;
    }

    return $sessions_with_urls;
  }

  /**
   * Create aliases for session proposals.
   *
   * @param array
   *   Array of session proposals.
   */
  protected function createAliases($sessions) {
    /** @var \Drupal\Core\Path\AliasStorage $aliasStorage */
    $aliasStorage = \Drupal::service('path.alias_storage');
    $pathAutoAliasCleaner = \Drupal::service('pathauto.alias_cleaner');
    foreach ($sessions as $key => $session) {
      $session_alias = '/sessions/' . $pathAutoAliasCleaner->cleanString($session[10]);
      if (!$aliasStorage->load(['alias' => $session_alias])) {
        $aliasStorage->save('/sessions/proposed/' . $key, $session_alias);
      }
    }
  }

}
