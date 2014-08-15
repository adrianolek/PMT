#!/bin/bash

chmod 777 /srv/uploads

if [[ ! -h /var/www/web/uploads ]]; then
  ln -s /srv/uploads /var/www/web/uploads
fi;

exec "$@"