# DrupalCamp Spain 2018

## Installation
1. Clone this repository.
2. Run 'composer install' in the document root.
3. Stop Apache and MySQL services. Then run `docker-compose up`.
5. Download a database dump from Jenkins (ask the maintainers for the URL).
   Alternatively, jump to the next section to install a sample database.
6. Open the site at http://drupal.docker.localhost:8000
8. Open the homepage as administrator with `docker exec -it dc2018_php_1 /bin/bash -c "cd web && drush uli"`.

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
