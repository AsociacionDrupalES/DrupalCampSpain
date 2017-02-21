<?php
/**
 * @file
 * @todo add info.
 */


namespace Drupal\dcamp\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dcamp\Entity\Dcamp;


/**
 * Class DcampController
 *
 * @package Drupal\dcamp\Controller
 */
class DcampController extends ControllerBase {

  /**
   * Controller for the landing page.
   *
   * @param \Drupal\dcamp\Entity\Dcamp $dcamp
   * @return array
   */
  public function landing(Dcamp $dcamp){
    // Return nothing. We only use blocks on the landing pages.
    return [
      '#markup' => '',
    ];
  }

  /**
   * Controller for the frontpage.
   */
  public function frontpage(){

  }


}