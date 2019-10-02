<?php

namespace Drupal\event_coming_soon_landing_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ScheduleController
 *
 * @package Drupal\event_schedule\Controller
 */
class ComingSoonLandingPageController extends ControllerBase {

  public function render() {
    $theme = [
      '#theme' => 'coming_soon_landing_page',
    ];

    $res = \Drupal::service('renderer')->render($theme);

    return new Response($res);
  }
}
