FROM php:8.1-fpm-alpine

# Install the Linux kernel headers 
RUN apk add --update linux-headers;

# Install dependencies
RUN set -ex \
  && apk --no-cache add \
    postgresql-dev \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions http

RUN apk --no-cache update && apk --no-cache add bash && apk --no-cache add git

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer

# Install Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash && apk add symfony-cli

# Add rights to www-data user on /var/www directory
RUN chown -R www-data:www-data /var/www

WORKDIR /var/www/