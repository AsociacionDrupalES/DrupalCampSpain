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

// If you need to test the newsletter widget, set your Mailchimp API key and List ID below.
$config['mailchimp.settings']['api_key'] = 'foo';
$config['mailchimp_signup.mailchimp_signup.dcamp_mailchimp']['mc_lists'] = [
  'bar' => 'bar',
];

/**
 * Set development friendly settings and configurations.
 *
 * @see sites/example.settings.local.php
 */
assert_options(ASSERT_ACTIVE, TRUE);
\Drupal\Component\Assertion\Handle::register();
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
$config['system.logging']['error_level'] = 'verbose';
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

// Disable render cache. (Do not change until after the site is installed.)
# $settings['cache']['bins']['render'] = 'cache.backend.null';

// Disable Dynamic Page cache.
# $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

$settings['extension_discovery_scan_tests'] = TRUE;
$settings['rebuild_access'] = TRUE;
$settings['skip_permissions_hardening'] = TRUE;

