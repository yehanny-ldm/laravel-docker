FROM php:7.3-apache

USER root

RUN apt-get update

# 1. development packages
RUN apt-get install -y \
    libssl-dev \
    openssl \
    libicu-dev \
    libbz2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    git \
    zip \
    curl \
    sudo \
    unzip \
    g++

RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    mbstring \
    pdo \
    pdo_mysql \
    zip \
    soap \
    gd

RUN docker-php-ext-configure \
    zip

# Install XDebug
# [Option] Install zsh
ARG INSTALL_ZSH="true"
# [Option] Upgrade OS packages to their latest versions
ARG UPGRADE_PACKAGES="false"
# [Option] Enable non-root Docker access in container
ARG ENABLE_NONROOT_DOCKER="true"
RUN pecl install xdebug mcrypt-1.0.2 && docker-php-ext-enable xdebug mcrypt\
    # xdebug.log=/var/www/html/xdebug.log \n\
    # On Windows and Mac use xdebug.client_host=host.docker.internal \n\
    && echo "\n\
    xdebug.mode=debug \n\
    xdebug.start_with_request=yes \n\
    xdebug.client_host=172.22.0.1 \n\
    xdebug.client_port=9002 \n\
    xdebug.remote_handler=dbgp \n\
    xdebug.idekey=VSCODE \n\
    xdebug.log=/var/www/html/xdebug.log \n\
    " >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    chown -R www-data:www-data /var/www/html/

# 2. Apache configs + document root.
RUN echo "ServerName laravel_app.test" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# 4. Start with base PHP config, then add extensions.
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# 5. Composer.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. We need a user with the same UID/GID as the host user
# so when we execute CLI commands, all the host file's permissions and ownership remain intact.
# Otherwise commands from inside the container would create root-owned files and directories.
ARG uid
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser
