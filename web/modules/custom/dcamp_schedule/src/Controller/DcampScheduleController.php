<?php
/**
 * @file
 * @todo add info.
 */


namespace Drupal\dcamp_schedule\Controller;

use Drupal\block_content\BlockContentViewBuilder;
use Drupal\block_content\Entity\BlockContent;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityRepository;
use Drupal\Core\Render\Renderer;


/**
 * Class DcampScheduleController
 *
 * @package Drupal\dcamp\Controller
 */
class DcampScheduleController extends ControllerBase {

  /**
   * Controller for the schedule.
   */
  public function schedule() {
    return [
      '#theme' => 'schedule',
      '#title' => $this->t('Schedule'),
    ];
  }

  /**
   * Title callback for frontpage.
   */
  public function getScheduleTitle(){
    return $this->t('Drupalcamp Schedule');
  }

}