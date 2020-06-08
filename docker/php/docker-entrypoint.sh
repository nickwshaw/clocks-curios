#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
	mkdir -p var/cache var/log public/media
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var public/media
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var public/media

	if [ "$APP_ENV" != 'prod' ]; then
	  echo 'Not production install composer dependencies and sylius assets'
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
		bin/console assets:install --no-interaction
		bin/console sylius:theme:assets:install public --no-interaction
	fi

fi

exec docker-php-entrypoint "$@"
