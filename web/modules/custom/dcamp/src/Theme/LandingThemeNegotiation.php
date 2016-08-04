<?php

namespace Drupal\dcamp\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;
use Drupal\dcamp\Entity\Dcamp;

/**
 * Negotiates the theme for the drupal camp landing page.
 */
class LandingThemeNegotiation implements ThemeNegotiatorInterface {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    $route_name =  $route_match->getRouteName();
    return 'entity.dcamp.canonical' == $route_name;
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    /** @var Dcamp $dcamp */
    $dcamp = $route_match->getParameter('dcamp');
    return $dcamp->get('landing_theme');
  }

}
