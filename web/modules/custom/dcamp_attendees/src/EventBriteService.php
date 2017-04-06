<?php

namespace Drupal\dcamp_attendees;

use Drupal\dcamp_attendees\Entity\IndividualSponsor;

class EventBriteService {

  /**
   * Return the list of individual sponsors
   *
   * @return \Drupal\dcamp_attendees\Entity\IndividualSponsor[]
   *   Array of IndividualSponsor objects
   */
  public function getIndividualSponsors() {
    $config = \Drupal::config('dcamp_attendees.settings');

    // Check if we are in developer mode.
    if ($config->get('debugging')) {
      $path = \Drupal::service('module_handler')->getModule('dcamp_attendees')->getPath();
      $individual_sponsors_json = json_decode(file_get_contents($path . '/fixtures/individual_sponsors.json'));
      foreach ($individual_sponsors_json as $attendee) {
        $individual_sponsors[] = new IndividualSponsor($attendee);
      }
    }
    else {
      // Request the list of attendees to EventBrite and filter individual sponsors.
      // TODO take into account paging as the following request returns just the
      // first 50 results.
      $client = \Drupal::httpClient();
      $response = $client->request('GET', 'https://www.eventbriteapi.com/v3/events/' . $config->get('event_id') . '/attendees', [
        'headers' => [
          'Authorization' => 'Bearer ' . $config->get('oauth_token'),
        ]
      ]);

      if ($response->getStatusCode() !== 200) {
        throw new \RuntimeException('Bad response from EventBrite');
      }

      $response_data = json_decode($response->getBody());
      $individual_sponsors = [];
      foreach ($response_data->attendees as $attendee) {
        if (in_array($attendee->ticket_class_name, ['Patrocinador individual', 'Patrocinador individual SIN entrada'])) {
          $individual_sponsors[] = new IndividualSponsor($attendee);
        }
      }
    }

    // @TODO cache this result.
    return $individual_sponsors;
  }

}