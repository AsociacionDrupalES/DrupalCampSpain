<?php

namespace Drupal\dcamp_attendees\ParamConverter;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;

class AttendeeParamConverter implements ParamConverterInterface {

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {
    $attendees = \Drupal::service('dcamp_attendees.eventbrite')->getAttendees();
    foreach ($attendees as $attendee) {
      if ($attendee->getId() == $value) {
        return $attendee;
      }
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function applies($definition, $name, Route $route) {
    return (!empty($definition['type']) && $definition['type'] == 'attendee');
  }
}