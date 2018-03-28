<?php

namespace Drupal\dcamp_attendees\Entity;

use Drupal\dcamp\NicknameParserTrait;
use Drupal\dcamp_attendees\EventBriteService;
use Drupal\dcamp_sessions\Entity\Session;
use JsonSerializable;

class Attendee implements JsonSerializable {

  use NicknameParserTrait;

  /**
   * The identifier in Eventbrite.
   *
   * @var int
   */
  protected $id;

  /**
   * The full name.
   *
   * @var string
   */
  protected $name;

  /**
   * The headshot.
   *
   * @var string
   */
  protected $headshot;

  /**
   * The Twitter profile.
   * @var string
   */
  protected $twitter;

  /**
   * The Drupal.org profile.
   *
   * @var string
   */
  protected $drupal;

  /**
   * The company name.
   *
   * @var string
   */
  protected $company;

  /**
   * The identifier of the ticket type.
   *
   * @var string
   */
  protected $ticketClassId;

  /**
   * Whether this attendee is speaking or not.
   *
   * @var boolean
   */
  protected $isSpeaker = FALSE;

  /**
   * The order id.
   *
   * @var string
   */
  protected $orderId;

  /**
   * The amount of extra tickets that this attendee bought.
   *
   * @var int
   */
  protected $extraTickets = 0;

  /**
   * The date when the attendee registered.
   *
   * @var string
   */
  protected $registered;

  /**
   * Attendee constructor.
   *
   * @param stdClass $attendee
   *   The raw object from EventBrite.
   */
  public function __construct($attendee) {
    $this->id = $attendee->id;
    $this->name = $attendee->profile->name;
    $this->company = !empty($attendee->profile->company) ? $attendee->profile->company : '';
    $this->ticketClassId = $attendee->ticket_class_id;
    $this->orderId = $attendee->order_id;
    $this->registered = $attendee->created;
    foreach ($attendee->answers as $answer) {
      if (!empty($answer->answer)) {
        if ($answer->question_id == EventBriteService::QUESTION_HEADSHOT) {
          $this->headshot = $answer->answer;
        }
        elseif ($answer->question_id == EventBriteService::QUESTION_TWITTER) {
          $this->twitter = $answer->answer;
        }
        elseif ($answer->question_id == EventBriteService::QUESTION_DRUPAL) {
          $this->drupal = $answer->answer;
        }
      }
    }
  }

  /**
   * @return int
   */
  public function getId(){
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId($id) {
    $this->id = $id;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName(string $name) {
    $this->name = $name;
  }

  /**
   * Returns the headshot URL.
   *
   * Checks if the URL ends with .jpg or .png. Otherwise it assumes
   * that this is not an image and returns an empty string. Some people
   * submitted Google Drive images, which are not an actual image)
   * @return string
   */
  public function getHeadshot() {
    $extension = pathinfo(parse_url($this->headshot, PHP_URL_PATH), PATHINFO_EXTENSION);
    if (!in_array($extension, ['png', 'gif', 'jpg', 'jpeg'])) {
      return '';
    }

    // Add https in cases if there is no scheme.
    $scheme = parse_url($this->headshot, PHP_URL_SCHEME);
    if (empty($scheme)) {
      $this->headshot = 'https://' . $this->headshot;
    }
    // Replace http by https.
    elseif ($scheme == 'http') {
      $this->headshot = str_replace('http', 'https', $this->headshot);
    }

    return $this->headshot;
  }

  /**
   * @param string $headshot
   */
  public function setHeadshot(string $headshot) {
    $this->headshot = $headshot;
  }

  /**
   * @return string
   */
  public function getTwitter() {
    return $this->twitter;
  }

  /**
   * @param string $twitter
   */
  public function setTwitter(string $twitter) {
    $this->twitter = $twitter;
  }

  /**
   * @return string
   */
  public function getDrupal() {
    return $this->drupal;
  }

  /**
   * @param string $drupal
   */
  public function setDrupal(string $drupal) {
    $this->drupal = $drupal;
  }

  /**
   * @return string
   */
  public function getCompany() {
    return $this->company;
  }

  /**
   * @param string $company
   */
  public function setCompany($company) {
    $this->company = $company;
  }

  /**
   * @return string
   */
  public function getTicketClassId() {
    return $this->ticketClassId;
  }

  /**
   * @param string $ticketClassId
   */
  public function setTicketClassId($ticket_class_id) {
    $this->ticketClassId = $ticket_class_id;
  }

  /**
   * @return string
   */
  public function getOrderId() {
    return $this->orderId;
  }

  /**
   * @param string $orderId
   */
  public function setOrderId($orderId) {
    $this->orderId = $orderId;
  }

  /**
   * @return int
   */
  public function getExtraTickets() {
    return $this->extraTickets;
  }

  /**
   * @param int $extra_tickets
   */
  public function setExtraTickets($extra_tickets) {
    $this->extraTickets = $extra_tickets;
  }

  /**
   * @return int
   */
  public function getRegistered() {
    return $this->registered;
  }

  /**
   * @param int $registered
   */
  public function setRegistered($registered) {
    $this->registered = $registered;
  }

  /**
   * Adds an extra ticket.
   */
  public function addExtraTicket() {
    $this->extraTickets++;
  }

  /**
   * Checks if this attendee bought extra tickets.
   *
   * @return bool
   *   TRUE if the attendee bought extra tickets.
   */
  public function hasExtraTickets() {
    return $this->extraTickets > 0;
  }

  /**
   * Checks if this attendee is an individual sponsor.
   *
   * @return bool
   *   TRUE if this attendee is an individual sponsor.
   */
  public function isIndividualSponsor() {
    return $this->ticketClassId == EventBriteService::TICKET_TYPE_INDIVIDUAL_SPONSOR;
  }

  /**
   * Returns the Twitter or Drupal Link URL.
   *
   * @return string
   *   A URL to the profile or an empty string if there is no link.
   */
  public function getProfileUrl() {
    $url = '';

    if (!empty($this->getTwitter())) {
      $url = $this->getTwitterUrl($this->getTwitter());
    }
    elseif (!empty($this->getDrupal())) {
      $url = $this->getDrupalUrl($this->getDrupal());
    }

    return $url;
  }

  /**
   * Returns the Twitter or Drupal nickname.
   *
   * @return string
   *   A URL to the profile or an empty string if there is no nickname.
   */
  public function getNickname() {
    $nickname = '';

    if (!empty($this->getTwitter())) {
      $nickname = $this->extractNickname($this->getTwitter());
      $nickname = '@' . $nickname;
    }
    elseif (!empty($this->getDrupal())) {
      $nickname = $this->extractNickname($this->getDrupal());
    }

    return $nickname;
  }

  /**
   * Prepares object for conversion to JSON.
   */
  public function jsonSerialize() {
    return [
      'id' => $this->getId(),
      'name' => $this->getName(),
      'company' => $this->getCompany(),
      'headshot' => $this->getHeadshot(),
      'twitter_url' => $this->getTwitter() ? $this->getTwitterUrl($this->getTwitter()) : '',
      'drupal_url' => $this->getDrupal() ? $this->getDrupalUrl($this->getDrupal()) : '',
      'individual_sponsor' => $this->isIndividualSponsor(),
      'is_speaker' => $this->getIsSpeaker(),
    ];
  }

  /**
   * @return bool
   */
  public function getIsSpeaker() {
    return $this->isSpeaker;
  }

  /**
   * @param bool $isSpeaker
   */
  public function setIsSpeaker($isSpeaker) {
    $this->isSpeaker = $isSpeaker;
  }

  /**
   * Checks whether this attendee speaks at a given session.
   *
   * @param Session $session
   *   A Session object.
   * @return bool
   *   TRUE if this speaker speaks at the given session.
   */
  public function speaksAt(Session $session) {
    if (!empty($this->getDrupal()) &&
      ($this->getDrupalUrl($this->getDrupal()) == $session->getDrupalUrl($session->getDrupal()))) {
      return TRUE;
    }
    elseif (!empty($this->getTwitter()) &&
      ($this->getTwitterUrl($this->getTwitter()) == $session->getTwitterUrl($session->getTwitter()))) {
      return TRUE;
    }
    return FALSE;
  }

}
