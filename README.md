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
drush en stage_file_proxy -y
```
7. Create a virtual host like `dc2017.local` and point it to the web
   directory of this project. If you choose a different hostname, then
   adjust `settings.php` accordingly.
8. Open the homepage as administrator with `drush uli`.

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


### Docker Environment
We based our local environment in the[https://github.com/keopx/docker-lamp](keopx's Docker-lamp).

#### Prerequisites
You need to have installer docker and docker-compose. 
Assure that you don't have running anything in the 80, 443, 1025, 3306 and 8025 ports
#### Use
Everything is configured to setup the local environment. Just follow this steps:

1. Host domain is set as `dc2017.local`, so add to your hosts files `127.0.0.1  dc2017.local`
2. There is a helper script, in `docker-lamp/local-docker.sh`. With this script you are able to:
    1. `./docker-lamp/local-docker.sh start`: Start services
    2. `./docker-lamp/local-docker.sh stop`: Stop services
    3. `./docker-lamp/local-docker.sh restart`: Restart services
    4. `./docker-lamp/local-docker.sh goto`: Get into ssh session on web_1 to execute composer, drush,...
    5. `./docker-lamp/local-docker.sh gotoroot`: Get into ssh session in web_1 as root
    6. `./docker-lamp/local-docker.sh status`: Check if service and docker compose is running.
    
We are aware that this docker configuration has some problems. Feel free to create any issue here. 

## API
The following resources are available:

* Pages: https://2017.drupalcamp.es/jsonapi/node/page
* Sponsors: https://2017.drupalcamp.es/jsonapi/node/sponsor
* Speakers: https://2017.drupalcamp.es/jsonapi/node/speaker
* Proposed Sessions: https://2017.drupalcamp.es/sessions/proposed?_format=json
* Selected Sessions: https://2017.drupalcamp.es/sessions/selected?_format=json
* Session detail: https://2017.drupalcamp.es/sessions/vue-meets-drupal-miguelangcaro?_format=json
* Sponsors: https://2017.drupalcamp.es/sponsors/list
* Attendees: https://2017.drupalcamp.es/attendees/list?_format=json
* Schedule: https://2017.drupalcamp.es/schedule?_format=json
