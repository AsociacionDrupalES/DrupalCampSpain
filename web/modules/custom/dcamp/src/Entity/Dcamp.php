<?php

/**
 * @file
 * Contains \Drupal\dcamp\Entity\Dcamp.
 */

namespace Drupal\dcamp\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\dcamp\DcampInterface;

/**
 * Defines the DrupalCamp Configuration entity.
 *
 * @ConfigEntityType(
 *   id = "dcamp",
 *   label = @Translation("DrupalCamp Configuration"),
 *   handlers = {
 *     "list_builder" = "Drupal\dcamp\DcampListBuilder",
 *     "form" = {
 *       "add" = "Drupal\dcamp\Form\DcampForm",
 *       "edit" = "Drupal\dcamp\Form\DcampForm",
 *       "delete" = "Drupal\dcamp\Form\DcampDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\dcamp\DcampHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "dcamp",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "landing_theme" = "landing_theme",
 *     "starting_date" = "starting_date"
 *   },
 *   links = {
 *     "canonical" = "/dcamp-landing/{dcamp}",
 *     "add-form" = "/admin/structure/dcamp/add",
 *     "edit-form" = "/admin/structure/dcamp/{dcamp}/edit",
 *     "delete-form" = "/admin/structure/dcamp/{dcamp}/delete",
 *     "collection" = "/admin/structure/dcamp"
 *   }
 * )
 */
class Dcamp extends ConfigEntityBase implements DcampInterface {
  /**
   * The DrupalCamp Configuration ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The DrupalCamp Configuration label.
   *
   * @var string
   */
  protected $label;

  /**
   * The starting date
   */
  protected $starting_date;

  /**
   * Landing Theme
   */
  protected $landing_theme;

  /**
   * @return Date
   */
  public function getStartingDate() {
    $this->get('starting_date');
  }

  /**
   * @return string Landing theme.
   */
  public function getLandingTheme() {
    $this->get('landing_theme');
  }

}