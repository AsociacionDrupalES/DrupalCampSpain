# Build a Tugboat preview from scratch
TIMESTAMP := $(shell date +%s)

tugboat-init:
	apt-get update
	apt-get install -y mysql-client wget
	ln -sf ${TUGBOAT_ROOT}/web /var/www/html
	composer install
	mysql -h mysql -u tugboat -ptugboat -e "create database demo;"
	cp /var/www/html/sites/default/tugboat.settings.php /var/www/html/sites/default/settings.local.php
	curl -u ${AUTH_USER}:'$(value AUTH_PASSWORD)' https://dev.drupalcamp.es/backup/dcamp2018.sql.gz?${TIMESTAMP} -o dcamp2018.sql.gz
	gunzip dcamp2018.sql.gz
	echo '$(value SERVICE_FILE)' > service-file.json
	cd web && \
		../vendor/bin/drush sql-cli < ../dcamp2018.sql && \
		../vendor/bin/drush updatedb -y -v && \
		../vendor/bin/drush config-import -y -v && \
		../vendor/bin/drush cr
	cd web && \
		../vendor/bin/drush en -y stage_file_proxy
