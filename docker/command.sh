#!/bin/bash

docker run --rm -t -i \
 --link pmt.mysql:mysql \
 --volumes-from pmt.uploads \
 -v $(pwd)/docker/php/entrypoint.sh:/entrypoint.sh \
 -v $(pwd):/var/www \
 -v $(pwd)/docker/php/php.ini:/etc/php5/fpm/php.ini \
 -v $(pwd)/docker/php/www.conf:/etc/php5/fpm/pool.d/www.conf \
 -w /var/www \
 pmt-php \
 ${@}