# 1. Требуемое ПО
* php 7.2 c расширениями
  * php-cli
  * php7.2-dom
  * php7.2-xml
  * php7.2-zip
  * php7.2-curl
  * php7.2-mbstring
* composer - https://getcomposer.org/
* Google chrome
* chromedriver - http://chromedriver.chromium.org/
* Java >= 1.8
* selenium-server-standalone-3.8.1.jar - http://selenium-release.storage.googleapis.com/index.html?path=3.8/
* git
* docker
* docker-compose

# 2. Локальный запуск
* Запустить selenium server:
```sh
$ java -jar selenium-server-standalone-3.8.1.jar -enablePassThrough false
```
* Установить зависимости:
```sh
$ composer install
```
* Запуск с GUI:
```sh
$ vendor/bin/codecept run acceptance --debug --html
```
* Запуск в headless режиме:
```sh
$ vendor/bin/codecept run acceptance --debug --html --env headless
```
* Исключить какую-либо группу тестов:
```sh
$ vendor/bin/codecept run acceptance --debug --html --skip-group exclude --env ${ENV}
```

# 3. Docker

```sh
docker-compose up --abort-on-container-exit
```
Отчёт будет в директории `tests/_output`