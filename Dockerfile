FROM php:8.1-cli-alpine

WORKDIR /app

RUN apk --update upgrade \
    && apk add --no-cache  \
    autoconf  \
    automake  \
    make  \
    gcc  \
    g++  \
    git  \
    bash  \
    icu-dev  \
    libzip-dev  \
    linux-headers

RUN pecl install xdebug-3.2.2

# allow non-root users have home
RUN mkdir -p /opt/home
RUN chmod 777 /opt/home
ENV HOME /opt/home

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN chmod +x /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1