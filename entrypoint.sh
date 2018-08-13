#!/bin/sh

sleep 5

./composer-install.sh
composer install

vendor/bin/codecept run acceptance --env ${ENV}