# Build a Tugboat preview from scratch
tugboat-init:
	apt-get update
	apt-get install -y mysql-client wget
	ln -sf ${TUGBOAT_ROOT}/web /var/www/html
	composer install
	mysql -h mysql -u tugboat -ptugboat -e "create database demo;"
	cp /var/www/html/sites/default/tugboat.settings.php /var/www/html/sites/default/settings.local.php
	cd web && \
		wget -O - -o /dev/null ${DATABASE_URL} | ../vendor/bin/drush sql-cli && \
		../vendor/bin/drush updatedb -y -v && \
		../vendor/bin/drush config-import -y -v && \
		../vendor/bin/drush cr
	composer require drupal/stage_file_proxy
	cd web && \
		../vendor/bin/drush en -y stage_file_proxy

# Update an existing preview
tugboat-update:
    # pull in fresh data, if applicable
    # call tugboat-build

# Start from a base preview
tugboat-build:
    # run application-specific script(s)
    # compile, uglify, etc.
