<?php
$databases = array (
  'default' =>
    array (
      'default' =>
        array (
          'database' => 'demo',
          'username' => 'tugboat',
          'password' => 'tugboat',
          'host' => 'mysql',
          'port' => '',
          'driver' => 'mysql',
          'prefix' => '',
        ),
    ),
);

$config_directories['sync'] = '../config';

// If you need to test the newsletter widget, set your Mailchimp API key and List ID below.
$config['mailchimp.settings']['api_key'] = 'foo';
$config['mailchimp_signup.mailchimp_signup.dcamp_mailchimp']['mc_lists'] = [
  'bar' => 'bar',
];

// Point Stage File Proxy to production.
$config['stage_file_proxy.settings']['origin'] = 'https://2018.drupalcamp.es';

// Session settings.
$config['dcamp_sessions.settings'] = [
  'debugging' => FALSE,
  'service_account_file' => '/var/lib/tugboat/service-file.json',
  'spreadsheet_id' => getenv('SESSIONS_SPREADSHEET_ID'),
  'spreadsheet_range' => 'Form Responses 1',
];

// Attendees settings.
$config['dcamp_attendees.settings'] = [
  'debugging' => FALSE,
  'event_id' => getenv('ATTENDEES_EVENT_ID'),
  'oauth_token' => getenv('ATTENDEES_OAUTH_TOKEN'),
];
