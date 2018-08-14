FROM ubuntu:18.04

RUN apt-get update && \
    apt-get install -y \
    wget \
    curl \
    php-cli \
    php7.2-dom \
    php7.2-xml \
    php7.2-zip \
    php7.2-curl \
    php7.2-mbstring

WORKDIR /project

ENTRYPOINT [ "./entrypoint.sh" ]
