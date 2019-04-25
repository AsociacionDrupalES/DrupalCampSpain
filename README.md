# DrupalCamp Spain 2018

## Installation

1. Clone this repository.
2. Run 'composer install' in the document root.
3. Copy `web/sites/default/default.settings.local.php` into
   `web/sites/default/settings.local.php`.
4. Open web/sites/default/settings.local.php and adjust the
   database connection. Create the database manually.
5. Download a database dump from Jenkins (ask the maintainers for the URL).
6. Install Drupal and then import the database with the following commands:
```bash
cd /path/to/repository/root
drush sql-cli < dcamp2018.sql
drush updb -y
drush cim sync -y
drush cr
drush en stage_file_proxy -y
```
7. Create a virtual host like `dc2018.local` and point it to the web
   directory of this project. If you choose a different hostname, then
   adjust `trusted_host_patterns` at `settings.php` accordingly.
8. Open the homepage as administrator with `drush uli`.

## Updating your local environment
Run `git checkout master && git pull`. Then repeat steps 5 onwards from the above section.

## Development

### Theme
1. Go to web/themes/dcamp_base_theme
1. Run 'npm install' (you might need to install node/npm first)
1. Install LiveReload for chrome (https://chrome.google.com/webstore/detail/livereload/jnihajbhpnppcggbcgedagnkighmdlei?hl=en)
1. Run 'gulp' in the terminal
1. Activate LiveReload in chrome
1. Happy styling!

### Configure Eventbrite
Edit web/modules/custom/dcamp_attendees/src/EventBriteService.php and update this variables:
```
  /**
   * Ticket types.
   */
  const TICKET_TYPE_GENERAL = 107881623;
  const TICKET_TYPE_INDIVIDUAL_SPONSOR = 104863188;
  const TICKET_TYPE_INDIVIDUAL_SPONSOR_NO_ACCESS = 82145718;
  const TICKET_TYPE_STUDENT = 82145719;
  const TICKET_TYPE_BEGINNER_TRACK = 82889072;

  /**
   * Question types.
   */
  const QUESTION_HEADSHOT = 21920431; // Is the user image field
  const QUESTION_TWITTER = 21920429;
  const QUESTION_DRUPAL = 21920430;
```


And add these lines to settings.local.php
```
// Attendees settings.
$config['dcamp_attendees.settings'] = [
  'debugging' => FALSE,
  'event_id' => '55000000416',
  'oauth_token' => '3h4g5j3h4gf534hjg56',
];
``` 
(You need to create a new "app" at https://www.eventbrite.es/account-settings/apps to get the oauth token).