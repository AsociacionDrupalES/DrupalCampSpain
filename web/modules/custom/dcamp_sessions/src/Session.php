<?php

namespace Drupal\dcamp_sessions;

/**
 * Class to interact with session data
 */
class Session {

  /**
   * Positions in sessionsGathered array
   */
  const NAME = 2;
  const DRUPAL_ORG = 3;
  const PHOTO = 4;
  const TWITTER = 5;
  const BIO = 6;
  const SESSION_TYPE = 7;
  const SESSION_LEVEL = 8;
  const LANGUAGE = 9;
  const TITLE= 10;
  const DESCRIPTION = 11;

  /**
   * @var string[]
   */
  private $sessionGathered;

  public function __construct( $session_gathered) {
    $this->sessionGathered = $session_gathered;
  }

  public function getAuthorsName() {
    return $this->sessionGathered[$this::NAME];
  }

  public function getAuthorsDrupalName() {
    return $this->sessionGathered[$this::DRUPAL_ORG];
  }

  public function getAuthorsPhoto() {
    return $this->sessionGathered[$this::PHOTO];
  }

  public function getAuthorsTwitter() {
    return $this->sessionGathered[$this::TWITTER];
  }

  public function getAuthorsBio() {
    return $this->sessionGathered[$this::BIO];
  }

  public function getType() {
    return $this->sessionGathered[$this::SESSION_TYPE];
  }

  public function getLevel() {
    return $this->sessionGathered[$this::SESSION_LEVEL];
  }

  public function getLanguage() {
    return $this->sessionGathered[$this::LANGUAGE];
  }

  public function getTitle() {
    return $this->sessionGathered[$this::TITLE];
  }

  public function getDescription() {
    return $this->sessionGathered[$this::DESCRIPTION];
  }
}
