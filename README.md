# DrupalCamp Spain 2017

## Installation
1. Clone this repository.
1. Run 'composer install' in the document root.
1. Copy web/sites/default/default.settings.local.php into
   web/sites/default/settings.local.php.
1. Open web/sites/default/settings.local.php and adjust it
   to your local environment needs.
1. Configure your web server so it points to the web directory.
1. Open the site in a web browser and go to core/install.php.
1. Select Config Installer as the installation profile.
1. At the config import step, find and select the configuration
   file at config/config.tar.gz.
1. Once the installation completes, you should see the landing
   page as the homepage.


## Development

### Theme
1. Go to web/themes/dcamp_base_theme
1. Run 'npm install' (you might need to install node/npm first)
1. Install LiveReload for chrome (https://chrome.google.com/webstore/detail/livereload/jnihajbhpnppcggbcgedagnkighmdlei?hl=en)
1. Run 'gulp' in the terminal
1. Activate LiveReload in chrome
1. Happy styling!
