# DrupalCamp Spain 2017

## Installation
1. Clone this repository.
2. Run 'composer install' in the document root.
3. Copy web/sites/default/default.settings.local.php into
   web/sites/default/settings.local.php.
4. Open web/sites/default/settings.local.php and adjust it
   to your local environment needs. Make sure that you create the database.
5. Download a database dump from Jenkins (ask the maintainers for the URL).
   Alternatively, jump to the next section to install a sample database.
6. Run the following commands to install the database dump and update the
   database:
```bash
cd web
drush sql-cli < dump.sql
drush updb -y
drush cim sync -y
drush cr
```
7. Open the homepage as administrator with `drush uli`.

### Installing without the production database
If you can't access to a production database dump, then use the following
commands to install a sample database:

```bash
drush si --config-dir=../config -y
drush cim sync -y
drush ev _dcamp_add_content_blocks_for_frontpage()
```

## Updating your local environment
Run `git checkout master && git pull`. Then repeat steps 5th onwards from the above section.

## Development

### Theme
1. Go to web/themes/dcamp_base_theme
1. Run 'npm install' (you might need to install node/npm first)
1. Install LiveReload for chrome (https://chrome.google.com/webstore/detail/livereload/jnihajbhpnppcggbcgedagnkighmdlei?hl=en)
1. Run 'gulp' in the terminal
1. Activate LiveReload in chrome
1. Happy styling!
