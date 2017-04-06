<?php

namespace Drupal\dcamp;

/**
 * Trait NicknameParserTrait
 *
 * Provides helper methods to extract nicknames and social network URLs from
 * raw data coming from external sources.
 */
trait NicknameParserTrait {

  /**
   * Removes the URL from a profile URL and keeps the nickname.
   *
   * @param string $url
   *   The profile URL.
   *
   * @return string
   *   The nickname.
   */
  protected function extractNickname($url) {
    $nickname = $url;

    // First extract everything until the last forwardslash.
    $slash_pos = strrpos($nickname, '/');
    if ($slash_pos) {
      $nickname = substr($nickname, $slash_pos + 1);
    }

    // Next, remove the @ symbol that Twitter nicknames may have.
    $nickname = str_replace('@', '', $nickname);

    return $nickname;
  }

  /**
   * Returns a Twitter URL out of a nickname.
   *
   * @param string $nickname
   *   The raw nickname.
   *
   * @return string
   *   The Twitter URL.
   */
  public function getTwitterUrl($nickname) {
    return 'https://twitter.com/' . $this->extractNickname($nickname);
  }

  /**
   * Returns a Drupal.org profile URL out of a nickname.
   *
   * @param string $nickname
   *   The raw nickname.
   *
   * @return string
   *   The Drupal.org profile URL.
   */
  public function getDrupalUrl($nickname) {
    return 'https://www.drupal.org/u/' . $this->extractNickname($nickname);
  }

}