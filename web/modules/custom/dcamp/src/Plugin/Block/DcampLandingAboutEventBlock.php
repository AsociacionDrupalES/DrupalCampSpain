<?php
/**
 * @file
 * Contains...
 */

namespace Drupal\dcamp\Plugin\Block;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Routing\RouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides a Description block with Countdown
 *
 * @Block(
 *   id = "dcamp_landing_about_event_block",
 *   admin_label = @Translation("DrupalCamp block for landing about event")
 * )
 */
class DcampLandingAboutEventBlock extends DcampLandingBlockBase implements ContainerFactoryPluginInterface{


  /**
   * @var RouteMatch
   */
  protected $currentRouteMatch;

  /**
   * Constructs a new Node Type object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $current_route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   *
   * @todo Add the countdown
   */
  public function build() {
    $build = parent::build();
    $dcamp = $this->currentRouteMatch->getParameter('dcamp');
    $build['#countdown'] = '@todo';
    return $build;
  }
}
