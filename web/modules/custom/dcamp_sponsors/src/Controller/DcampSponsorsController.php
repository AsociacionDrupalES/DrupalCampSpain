<?php

namespace Drupal\dcamp_sponsors\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DcampSponsorsController extends ControllerBase {

  /**
   * The amount of seconds to cache sponsor listings.
   *
   * @var int
   */
  protected $maxAge = 120;

  /**
   * Lists sponsors
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing the list of sponsors.
   */
  public function listSponsors() {
    /** @var  $node_storage \Drupal\Core\Entity\EntityStorageInterface */
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $sponsors = [];

    // First, fetch sponsors stored in the database.
    $sponsor_nids = $node_storage
      ->getQuery()
      ->condition('type', 'sponsor')
      ->execute();
    $sponsor_nodes = $node_storage->loadMultiple($sponsor_nids);
    foreach ($sponsor_nodes as $sponsor) {
      $sponsors[] = [
        'name' => $sponsor->getTitle(),
        'type' => $sponsor->get('field_sponsor_type')->getString(),
        'url' => $sponsor->get('field_url')->getString(),
        'logo' => file_create_url($sponsor->get('field_logo')->entity->getFileUri()),
      ];
    }

    // Next, find individual sponsors from EventBrite.
    $individual_sponsors = \Drupal::service('dcamp_attendees.eventbrite')->getIndividualSponsors();
    /** @var \Drupal\dcamp_attendees\Entity\Attendee $sponsor */
    foreach ($individual_sponsors as $sponsor) {
      $sponsors[] = [
        'name' => $sponsor->getName(),
        'type' => 'individual',
        'url' => $sponsor->getProfileUrl(),
        'logo' => $sponsor->getHeadshot(),
      ];
    }

    // Prepare and send response.
    $headers = [
      'max-age' => $this->maxAge,
    ];
    return new JsonResponse($sponsors, Response::HTTP_OK, $headers);
  }

}
