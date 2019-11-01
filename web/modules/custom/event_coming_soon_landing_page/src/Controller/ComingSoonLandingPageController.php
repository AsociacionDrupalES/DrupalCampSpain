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
    $variables['#attached']['library'][] = 'fluffiness/node-preview';
    $theme = [
      '#theme' => 'coming_soon_landing_page',
      '#attached' => ['library' => ['event_coming_soon_landing_page/landing-page']]
    ];

    $res = \Drupal::service('renderer')->render($theme);

    return new Response($res);
  }
}
