<?php

namespace Drupal\event_schedule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ScheduleController
 *
 * @package Drupal\event_schedule\Controller
 */
class ScheduleController extends ControllerBase {

  public function renderSchedule() {
    return [
      '#theme' => 'schedule',
      '#title' => $this->t('Schedule'),
    ];
  }
}
