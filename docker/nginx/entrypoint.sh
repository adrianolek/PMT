#!/bin/bash

if [[ ! -h /var/www/web/uploads ]]; then
  ln -s /srv/uploads /var/www/web/uploads
fi;

exec "$@"