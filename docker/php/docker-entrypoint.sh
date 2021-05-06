#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
  set -- php-fpm "$@"
fi

simpleLoad=false
fullLoad=false
if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
  simpleLoad=true
  fullLoad=true
elif [ "$1" = 'composer' ]; then
  simpleLoad=true
fi

if $simpleLoad ; then
  # Устанавливаем настройки PHP
  PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-production"
  if [ "$APP_ENV" = 'dev' ]; then
    PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-development"
  fi
  ln -sf "$PHP_INI_RECOMMENDED" "$PHP_INI_DIR/php.ini"

  # Создаём директории, необходимые для работы приложения
  mkdir -p data/cache data/log
  setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX data || true
  setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX data || true
fi

  if $fullLoad ; then
    if [ "$GIT_TOKEN" ] && [ ! -f "../../auth.json" ] ; then
      composer config gitlab-token.git.amocrm.ru "$GIT_TOKEN"
    fi
    # Если это инициализация проекта в dev окружении, то установим зависимости
    if [ "$APP_ENV" = 'dev' ] && [ ! -d "vendor" ] && [ -f "../../auth.json" ] ; then
      composer install --prefer-dist --no-progress --no-suggest --no-interaction
    fi

    # В dev окружении не нужен кэш фабрик и конфигов
    if [ "$APP_ENV" = 'dev' ]; then
      # Очень интересное решение кидать Exception, если цель команды уже достигнута ранее
      composer run development-enable || true
      rm -fr src/AoT/gen
      rm -f data/cache/*.php
    fi
  fi

if $simpleLoad ; then
  # Дропаем конфиг приложения, т.к. переменные окружения,
  # с которыми оно собиралось, на бою отличаются.
  rm -f data/cache/config-cache.php
fi

#if $fullLoad ; then
#  echo "Waiting for db to be ready..."
#  until bin/console db:info >/dev/null 2>&1; do
#    sleep 1
#  done
#
#  echo "Executing migrations..."
#  bin/console migrations:migrate
#fi

exec docker-php-entrypoint "$@"
