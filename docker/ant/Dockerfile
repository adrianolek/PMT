FROM stackbrew/ubuntu:14.04

ENV DEBIAN_FRONTEND noninteractive

RUN set -e; \
  apt-get update -y; \
  apt-get install -y php5-cli php5-intl npm ant curl git; \
  npm install -g uglify-js uglifycss; \
  ln -s /usr/bin/nodejs /usr/bin/node; \
  curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer; \
  apt-get clean; \
  rm -rf /var/lib/apt/lists/*

WORKDIR /var/www
ENTRYPOINT ["/usr/bin/ant"]

CMD ["prod"]
