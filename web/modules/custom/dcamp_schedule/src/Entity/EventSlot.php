<?php

namespace Drupal\dcamp_schedule\Entity;

use JsonSerializable;

/**
 * Defines an event slot such as the Opening session.
 */
class EventSlot implements JsonSerializable {

  /**
   * The session URL.
   *
   * @var string
   */
  protected $url;

  /**
   * The title of the session.
   *
   * @var string
   */
  protected $title;

  /**
   * The day assigned.
   *
   * @var string
   */
  protected $day;

  /**
   * The time slot assigned.
   *
   * @var string
   */
  protected $timeSlot;

  /**
   * The room name assigned.
   *
   * @var string
   */
  protected $roomName;

  /**
   * Session constructor.
   *
   * @param $raw_session
   *   The JSON representation of the session from Google Sheets.
   */
  public function __construct($raw_session) {
    $this->url = $raw_session['url'];
    $this->title = $raw_session['title'];
    $this->day = $raw_session['day'];
    $this->timeSlot = $raw_session['time_slot'];
    $this->roomName = $raw_session['room_name'];
  }

  /**
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * @param string $url
   */
  public function setUrl($url) {
    $this->url = $url;
  }

  /**
   * @return string
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * @param string $title
   */
  public function setTitle($title) {
    $this->title = $title;
  }

  /**
   * @return string
   */
  public function getDay() {
    return $this->day;
  }

  /**
   * @param string $day
   */
  public function setDay($day) {
    $this->day = $day;
  }

  /**
   * @return string
   */
  public function getTimeSlot() {
    return $this->timeSlot;
  }

  /**
   * @param string $timeSlot
   */
  public function setTimeSlot($timeSlot) {
    $this->timeSlot = $timeSlot;
  }

  /**
   * @return string
   */
  public function getRoomName() {
    return $this->roomName;
  }

  /**
   * @param string $roomName
   */
  public function setRoomName($roomName) {
    $this->roomName = $roomName;
  }

  /**
   * Prepares object for conversion to JSON.
   */
  public function jsonSerialize() {
    return [
      'url' => $this->getUrl(),
      'title' => $this->getTitle(),
      'day' => $this->getDay() ?: '',
      'room_name' => $this->getRoomName() ?: '',
      'time_slot' => $this->getTimeSlot() ?: '',
    ];
  }

}
