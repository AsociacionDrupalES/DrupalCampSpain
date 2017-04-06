<?php

namespace Drupal\dcamp_attendees;

use Drupal\dcamp_attendees\Entity\IndividualSponsor;

class EventBriteService {

  /**
   * The cache id to store the list of attendees.
   *
   * @var string
   */
  protected $attendees_cid = 'dcamp_attendees:eventbrite_attendees';

  /**
   * The time to keep the list of attendees cached.
   *
   * Notice that you need to use this as strtotime($this->attendees_cid)
   * to set the right expiration time..
   *
   * @var string
   */
  protected $attendees_lifetime = '+5 minutes';

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
    }
    else {
      // Check if there is a cached value and it has not expire.
      $data = NULL;
      if ($cache = \Drupal::cache()->get($this->attendees_cid)) {
        $individual_sponsors_json = $cache->data;
      }
      if (($cache == FALSE) || ($cache->expire < time())) {
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
        $individual_sponsors_json = $response_data->attendees;

        // Store this data in cache.
        \Drupal::cache()->set($this->attendees_cid, $individual_sponsors_json, strtotime($this->attendees_lifetime));
      }
    }

    // Extract individual sponsors from the list of attendees.
    foreach ($individual_sponsors_json as $attendee) {
      if (in_array($attendee->ticket_class_name, ['Patrocinador individual', 'Patrocinador individual SIN entrada'])) {
        $individual_sponsors[] = new IndividualSponsor($attendee);
      }
    }

    return $individual_sponsors;
  }

}