#!/bin/sh

./composer-install.sh
composer install

vendor/bin/codecept run acceptance --debug --html --skip-group exclude --env ${ENV}