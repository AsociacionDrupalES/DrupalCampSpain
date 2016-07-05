<?php
/**
 * @file
 * Default local settings.
 *
 * Adjust the following for your local environment.
 */

$databases['default']['default'] = array(
  'database' => 'dc2017',
  'username' => 'root',
  'password' => '',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

// There is no need to change the following setting.
$config_directories['sync'] = 'sites/default/files/config_42L9pXMBOiVVCE4Uwm980e5dPPvCUGGohXIDjAnfPDhs5jR-iDRJVuKXs1kVxcDX6vB0TPaNeQ/sync';

// If you need to test the newsletter widget, set your Mailchimp API key below.
$config['mailchimp.settings']['api_key'] = 'foo';
