<?php

namespace Drupal\dcamp_sessions\Entity;

use Drupal\dcamp\NicknameParserTrait;
use JsonSerializable;

/**
 * Class to interact with session data
 */
class Session implements JsonSerializable {

  use NicknameParserTrait;

  /**
   * The position of the full name in the spreadsheet.
   */
  const NAME = 2;

  /**
   * The position of the Drupal.org profile in the spreadsheet.
   */
  const DRUPAL_ORG = 3;

  /**
   * The position of the headshot in the spreadsheet.
   */
  const HEADSHOT = 4;

  /**
   * The position of the Twitter profile in the spreadsheet.
   */
  const TWITTER = 5;

  /**
   * The position of the bio in the spreadsheet.
   */
  const BIO = 6;

  /**
   * The position of the session type in the spreadsheet.
   */
  const SESSION_TYPE = 7;

  /**
   * The position of the session level in the spreadsheet.
   */
  const SESSION_LEVEL = 8;

  /**
   * The position of the language in the spreadsheet.
   */
  const LANGUAGE = 9;

  /**
   * The position of the title in the speadsheet.
   */
  const TITLE= 10;

  /**
   * The position of the description in the spreadsheet.
   */
  const DESCRIPTION = 11;

  /**
   * The session URL.
   *
   * @var string
   */
  protected $url;

  /**
   * The name of the presenter.
   *
   * @var string.
   */
  protected $name;

  /**
   * The Drupal.org profile URL.
   *
   * @var string
   */
  protected $drupal;

  /**
   * The headshot URL.
   *
   * @var string
   */
  protected $headshot;

  /**
   * The TWitter URL.
   *
   * @var string
   */
  protected $twitter;

  /**
   * The bio of the presenter.
   *
   * @var string
   */
  protected $bio;

  /**
   * The type of the session.
   *
   * @var string
   */
  protected $type;

  /**
   * The level of the session.
   *
   * @var string
   */
  protected $level;

  /**
   * The language of the session.
   *
   * @var string
   */
  protected $language;

  /**
   * The title of the session.
   *
   * @var string
   */
  protected $title;

  /**
   * The description of the session.
   *
   * @var string
   */
  protected $description;

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
    $this->name = $raw_session[$this::NAME];
    $this->drupal = $this->extractNickname($raw_session[$this::DRUPAL_ORG]);
    $this->headshot = $raw_session[$this::HEADSHOT];
    $this->twitter = $this->extractNickname($raw_session[$this::TWITTER]);
    $this->bio = $raw_session[$this::BIO];
    $this->type = $raw_session[$this::SESSION_TYPE];
    $this->level = $raw_session[$this::SESSION_LEVEL];
    $this->language = $raw_session[$this::LANGUAGE];
    $this->title = $raw_session[$this::TITLE];
    $this->description = $raw_session[$this::DESCRIPTION];
  }

  /**
   * @return mixed
   */
  public function getBio() {
    return $this->bio;
  }

  /**
   * @param mixed $bio
   */
  public function setBio($bio) {
    $this->bio = $bio;
  }

  /**
   * @return mixed
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * @param mixed $description
   */
  public function setDescription($description) {
    $this->description = $description;
  }

  /**
   * @return string
   */
  public function getDrupal(){
    return $this->drupal;
  }

  /**
   * @param string $drupal
   */
  public function setDrupal($drupal) {
    $this->drupal = $drupal;
  }

  /**
   * @return mixed
   */
  public function getHeadshot() {
    return $this->headshot;
  }

  /**
   * @param mixed $headshot
   */
  public function setHeadshot($headshot) {
    $this->headshot = $headshot;
  }

  /**
   * @return mixed
   */
  public function getLanguage() {
    return $this->language;
  }

  /**
   * @param mixed $language
   */
  public function setLanguage($language) {
    $this->language = $language;
  }

  /**
   * @return mixed
   */
  public function getLevel() {
    return $this->level;
  }

  /**
   * @param mixed $level
   */
  public function setLevel($level) {
    $this->level = $level;
  }

  /**
   * @return mixed
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * @param mixed $title
   */
  public function setTitle($title) {
    $this->title = $title;
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
  public function setTwitter($twitter) {
    $this->twitter = $twitter;
  }

  /**
   * @return mixed
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @param mixed $type
   */
  public function setType($type) {
    $this->type = $type;
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
      'bio' => $this->getBio(),
      'description' => $this->getDescription(),
      'drupal_nick' => $this->getDrupal(),
      'drupal_url' => $this->getDrupal() ? $this->getDrupalUrl($this->getDrupal()) : '',
      'headshot' => $this->getHeadshot(),
      'language' => $this->getLanguage(),
      'name' => $this->getName(),
      'level' => $this->getLevel(),
      'type' => $this->getType(),
      'title' => $this->getTitle(),
      'twitter_nick' => $this->getTwitter(),
      'twitter_url' => $this->getTwitter() ? $this->getTwitterUrl($this->getTwitter()) : '',
      'bio' => $this->getBio(),
      'description' => $this->getDescription(),
      'url' => $this->getUrl(),
      'room_name' => $this->getRoomName() ?: '',
      'time_slot' => $this->getTimeSlot() ?: '',
      'day' => $this->getDay() ?: '',
    ];
  }

}
