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
