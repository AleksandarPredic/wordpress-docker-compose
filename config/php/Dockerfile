# https://hub.docker.com/_/wordpress/
FROM wordpress:php7.4

# xdebug-3.1.5 is compatible with PHP 7.4.9, later versions of xdebug require PHP 8
RUN pecl install xdebug-3.1.5 \
    && docker-php-ext-enable xdebug

#Install WP Cli
# wp-cli
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x wp-cli.phar && \
    mv wp-cli.phar /usr/local/bin/wpp
# Add flag so we don't get bored with --allow-root adding manually for each command
RUN echo "#!/bin/sh\n/usr/local/bin/wpp --allow-root \"\$@\"" > /usr/local/bin/wp && \
    chmod +x /usr/local/bin/wp

# Install composer globally on debian 11
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer \