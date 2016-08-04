<?php
/**
 * @file
 * @todo add info.
 */


namespace Drupal\dcamp\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dcamp\Entity\Dcamp;


class DcampController extends ControllerBase {
  public function landing(Dcamp $dcamp){
    // Return nothing. We only use blocks on the landing pages.
    return [
      '#markup' => '',
    ];
  }
}