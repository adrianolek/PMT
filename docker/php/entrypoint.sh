#!/bin/bash

if [[ ! -d /var/www/web/uploads ]]; then
  install -d -m 0777 /srv/data/uploads
fi;

if [[ ! -h /var/www/web/uploads ]]; then
  ln -s /srv/data/uploads /var/www/web/uploads
fi;

exec "$@"