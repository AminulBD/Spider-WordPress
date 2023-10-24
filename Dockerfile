FROM wordpress:php7.4-apache

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY --from=wordpress:cli /usr/local/bin/wp /usr/local/bin/wp
