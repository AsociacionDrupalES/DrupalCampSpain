# Build a Tugboat preview from scratch
tugboat-init:
	apt-get update
	apt-get install -y mysql-client wget
	ln -sf ${TUGBOAT_ROOT}/web /var/www/html
	composer install
	mysql -h mysql -u tugboat -ptugboat -e "create database demo;"
	cp /var/www/html/sites/default/tugboat.settings.php /var/www/html/sites/default/settings.local.php
	curl -u ${AUTH_USER}:${AUTH_PASSWORD} https://dev.drupalcamp.es/backup/dcamp2018.sql.gz?$(date +%s) -o dcamp2018.sql.gz
	gunzip dcamp2018.sql.gz
	cd web && \
		../vendor/bin/drush sql-cli < ../dcamp2018.sql && \
		../vendor/bin/drush updatedb -y -v && \
		../vendor/bin/drush config-import -y -v && \
		../vendor/bin/drush cr
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
