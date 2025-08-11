FROM laravelsail/php82-composer

RUN docker-php-ext-install pdo pdo_mysql
