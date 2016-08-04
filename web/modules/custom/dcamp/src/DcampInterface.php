<?php

/**
 * @file
 * Contains \Drupal\dcamp\DcampInterface.
 */

namespace Drupal\dcamp;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining DrupalCamp Configuration entities.
 */
interface DcampInterface extends ConfigEntityInterface {

  public function getStartingDate();

  public function getLandingTheme();

}
