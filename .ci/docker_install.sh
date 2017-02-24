#!/usr/bin/env bash

[[ ! -e /.dockerenv ]] && exit 0

set -xe

apt-get update -yqq

apt-get install -yqq git \
    curl \
    libicu-dev \
    libcurl3-dev \
    wget \
    unzip \
    zlib1g-dev

docker-php-ext-install curl json zip

pecl install xdebug
docker-php-ext-enable xdebug

wget https://composer.github.io/installer.sig -O - -q | tr -d '\n' > installer.sig
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === file_get_contents('installer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php'); unlink('installer.sig');"
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

composer install --no-progress
