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

### Contributing
1. Fork this repository and clone it.
1. Follow the installation instructions above.
1. Checkout a new branch out of master. If there is an issue, use the issue
   number at the start of the branch name, such as 1-foo.
1. Work. Try to create commits as you add functionality. You can use this
   template for your commits:
```
[#1] 50 chars one-liner that describes your changes

This is a longer description of the chanes in this commit. You can
use lists, links, and whatever you need here.
```
1. Push your branch and create a pull request. The better you describe it,
   the easier it will be to review and merge.
1. If you are changing configuration, save both config.tar.gz and its
contents in config. config/config.tar.gz is used for the installation, while
having the list of exported files within the config directory helps us to
see the differences in configuration that a pull request introduces.
