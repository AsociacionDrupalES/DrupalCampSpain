<?php

namespace Drupal\dcamp_sessions;

use Drupal\Component\Utility\Xss;

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
    return Xss::filter($this->sessionGathered[$this::NAME]);
  }

  public function getAuthorsDrupalName() {
    $user_input = Xss::filter($this->sessionGathered[$this::DRUPAL_ORG]);
    $url = NULL;
    if (strpos($user_input, "drupal.org") > 4) {
      // The input is like https://drupal.org/u/username or
      // https://drupal.org/user/{user_id}
      $url = $user_input;
    }
    elseif (strpos($user_input,"/") == FALSE) {
      // The used sent his/her drupal username, like 'isholgueras'
      $url = "https://drupal.org/u/" . $user_input;
    }
    return $url;
  }

  public function getAuthorsPhoto() {
    return Xss::filter($this->sessionGathered[$this::PHOTO]);
  }

  public function getAuthorsTwitter() {
    $user_input = Xss::filter($this->sessionGathered[$this::TWITTER]);
    $url = NULL;
    if (strpos($user_input, "twitter.com") > 4) {
      // The input is like https://twitter.com/username or
      // https://twitter.com/{user_id}
      $url = $user_input;
    }
    elseif (strpos($user_input,"@") !== FALSE) {
      // The used sent his/her drupal username, like '@isholgueras'
      $username = explode("@", $user_input)[1];
      $url = "https://twitter.com/" . $username;
    }
    elseif (strpos($user_input,"/") === FALSE) {
      // The used sent his/her twitter username, like 'isholgueras'
      $url = "https://twitter.com/" . $user_input;
    }
    return $url;
  }

  public function getAuthorsBio() {
    return Xss::filter($this->sessionGathered[$this::BIO]);
  }

  public function getType() {
    return Xss::filter($this->sessionGathered[$this::SESSION_TYPE]);
  }

  public function getLevel() {
    return Xss::filter($this->sessionGathered[$this::SESSION_LEVEL]);
  }

  public function getLanguage() {
    return Xss::filter($this->sessionGathered[$this::LANGUAGE]);
  }

  public function getTitle() {
    return Xss::filter($this->sessionGathered[$this::TITLE]);
  }

  public function getDescription() {
    return Xss::filter($this->sessionGathered[$this::DESCRIPTION]);
  }
}
