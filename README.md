## Setup localhost
After cloning the repo
- Change to the "dev" branch.
- Create `settings.local.php` and fill it with this:
```php
<?php

$databases['default']['default'] = array(
  'database' => '[PUT_YOUR_DB_NAME]',
  'username' => '[PUT_YOUR_DB_USER]',
  'password' => '[PUT_YOUR_DB_PASS]',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

$settings['cache']['bins']['page'] = 'cache.backend.null';
```
- Ensure `sites/development.services.yml` is configured properly:
```yaml
# Local development services.
#
# To activate this feature, follow the instructions at the top of the
# 'example.settings.local.php' file, which sits next to this file.
parameters:
  http.response.debug_cacheability_headers: true
  twig.config:
    debug: true
    auto_reload: true
    cache: true
services:
  cache.backend.null:
    class: Drupal\Core\Cache\NullBackendFactory
```
- Import `initdb.sql` located at the project root.
- Run `composer install && drush cr && drush cim -y`

NOTES:
* To access as admin use `drush uli`.
* If you need `files` dir you can target it with `stage_file_proxy` module or asking for a zipped copy.


## Activate "Coming soon" landing page
- Go to `admin/config/system/site-information` and set `/coming-soon` as "Default front page".
when you are ready to release the real page go to `admin/config/system/site-information` and set `/node/1` as "Default front page".

NOTE: If you want to modify the landing page go to `themes/coming_soon_th/templates/layout`. There you will find all HTML and used assets for the landing page.
