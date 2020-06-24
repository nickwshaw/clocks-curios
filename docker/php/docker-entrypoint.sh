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
	else
	  echo 'Production no composer install. Its all here already baby'
	fi

	if [ "$APP_ENV" != 'prod' ]; then
    until bin/console doctrine:query:sql "select 1" >/dev/null 2>&1; do
        (>&2 echo "Waiting for MySQL to be ready...")
      sleep 1
    done
	fi

	if [ "$APP_ENV" != 'prod' ]; then
    if [ "$(ls -A src/Migrations/*.php 2> /dev/null)" ]; then
      bin/console doctrine:migrations:migrate --no-interaction
    fi
  fi
fi

exec docker-php-entrypoint "$@"
