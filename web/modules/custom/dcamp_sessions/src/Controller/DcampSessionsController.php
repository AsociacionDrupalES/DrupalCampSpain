<?php

namespace Drupal\dcamp_sessions\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\dcamp_sessions\Entity\Session;
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
   * @param string $session_type
   *   The type of session to show. Defaults to proposed sessions.
   *
   * @return mixed
   *   JsonResponse when requested via API request. A render array
   *   otherwise.
   */
  public function listSessions($session_type) {
    /** @var \Drupal\dcamp_sessions\SessionProposalsService $sessions_service */
    $sessions_service = \Drupal::service('dcamp_sessions.proposals');

    // Filter out selected sessions if needed.
    if ($session_type == 'selected') {
      $sessions = $sessions_service->getSelected();
    }
    else {
      $sessions = $sessions_service->getProposals();
    }

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
      '#title' => $this->getSessionsTitle($session_type),
      '#items' => $list_items,
      '#cache' => [
        'max-age' => $this->maxAge,
      ],
    ];
  }

  /**
   * Returns the sessions listing title.
   *
   * Also does rudimentary validation, since we could not figure out
   * how to use the Choices route param constraint.
   *
   * @param string $session_type
   *   The type of session to show. Defaults to proposed sessions.
   */
  public function getSessionsTitle($session_type) {
    $title = '';

    if ($session_type == 'proposed') {
      $title = 'Proposed sessions';
    }
    elseif ($session_type == 'selected') {
      $title = 'Selected sessions';
    }

    return $title;
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
    $sessions = \Drupal::service('dcamp_sessions.proposals')->getProposals();
    if (empty($sessions[$submission_id])) {
      throw new BadRequestHttpException(t('https://i.makeagif.com/media/6-02-2015/UdiNwN.gif'));
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

}
