<?php
/**
 * @file
 * @todo add info.
 */


namespace Drupal\dcamp_schedule\Controller;

use Drupal\block_content\BlockContentViewBuilder;
use Drupal\block_content\Entity\BlockContent;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityRepository;
use Drupal\Core\Render\Renderer;
use Drupal\dcamp_schedule\Entity\EventSlot;
use Drupal\dcamp_sessions\Entity\Session;
use Drupal\dcamp_sessions\SessionProposalsService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class DcampScheduleController
 *
 * @package Drupal\dcamp\Controller
 */
class DcampScheduleController extends ControllerBase {

  /**
   * The amount of seconds to cache session listings.
   *
   * @var int
   */
  protected $maxAge = 120;

  /**
   * Controller for the schedule.
   */
  public function schedule() {

    // Check if this is an API request.
    if (\Drupal::request()->query->get('_format') == 'json') {
      $sessions = \Drupal::service('dcamp_sessions.proposals')->getSelected();
      $sessions = $this->addEventSlots($sessions);
      // Prepare and send response.
      $headers = [
        'max-age' => $this->maxAge,
      ];
      return new JsonResponse($sessions, Response::HTTP_OK, $headers);
    }

    return [
      '#theme' => 'schedule',
      '#title' => $this->t('Schedule'),
    ];
  }

  /**
   * Title callback for frontpage.
   */
  public function getScheduleTitle(){
    return $this->t('Drupalcamp Schedule');
  }

  /**
   * Adds event slots such as the Opening session.
   *
   * @param Session[]
   *   An array of Session objects.
   *
   * @return array
   *   An array with Session objects and EventSlot ones.
   */
  public function addEventSlots($sessions) {
    $sessions[] = new EventSlot([
      'url' => '',
      'title' => 'Registration - Badge Pick-Up',
      'day' => SessionProposalsService::FRIDAY,
      'room_name' => '',
      'time_slot' => '08:30',
    ]);
    $sessions[] = new EventSlot([
      'url' => '',
      'title' => 'Opening session',
      'day' => SessionProposalsService::FRIDAY,
      'room_name' => SessionProposalsService::AUDITORIO,
      'time_slot' => '09:30',
    ]);
    $sessions[] = new EventSlot([
      'url' => '/jsonapi/node/page/3f23a247-7e3d-4ef7-8af4-5cbcb6b2e553',
      'title' => 'Business Day',
      'day' => SessionProposalsService::FRIDAY,
      'room_name' => SessionProposalsService::POLIVALENTE,
      'time_slot' => '10:00',
    ]);
    $sessions[] = new EventSlot([
      'url' => '',
      'title' => 'Group photo',
      'day' => SessionProposalsService::FRIDAY,
      'room_name' => '',
      'time_slot' => '13:00',
    ]);
    $sessions[] = new EventSlot([
      'url' => '/jsonapi/node/page/7fa9c38d-6b39-4021-b8c0-30c4e92a74e4',
      'title' => 'Lunchtime break',
      'day' => SessionProposalsService::FRIDAY,
      'room_name' => '',
      'time_slot' => '13:00',
    ]);
    $sessions[] = new EventSlot([
      'url' => '/jsonapi/node/page/b7276dfa-ce0f-44bf-8247-bd7fabc0cf59',
      'title' => 'Spanish Drupal Association assembly',
      'day' => SessionProposalsService::FRIDAY,
      'room_name' => SessionProposalsService::AUDITORIO,
      'time_slot' => '18:00',
    ]);
    $sessions[] = new EventSlot([
      'url' => '',
      'title' => 'Coffee break',
      'day' => SessionProposalsService::SATURDAY,
      'room_name' => '',
      'time_slot' => '11:00',
    ]);
    $sessions[] = new EventSlot([
      'url' => '/jsonapi/node/page/7fa9c38d-6b39-4021-b8c0-30c4e92a74e4',
      'title' => 'Lunchtime break',
      'day' => SessionProposalsService::SATURDAY,
      'room_name' => '',
      'time_slot' => '11:00',
    ]);
    $sessions[] = new EventSlot([
      'url' => '',
      'title' => 'Closing session',
      'day' => SessionProposalsService::SATURDAY,
      'room_name' => SessionProposalsService::AUDITORIO,
      'time_slot' => '18:30',
    ]);
    $sessions[] = new EventSlot([
      'url' => '/jsonapi/node/page/6013fc1d-5bdc-43a3-88bf-c34ac11da1ae',
      'title' => 'Party',
      'day' => SessionProposalsService::SATURDAY,
      'room_name' => '',
      'time_slot' => '21:00',
    ]);

    return $sessions;
  }

}