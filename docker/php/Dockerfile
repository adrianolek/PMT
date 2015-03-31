FROM stackbrew/ubuntu:14.04

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -y; \
  apt-get install -y php5-fpm php5-cli php5-mysqlnd php5-intl php5-imagick php5-xdebug mysql-client; \
  apt-get clean; \
  rm -rf /var/lib/apt/lists/*

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]

CMD ["/usr/sbin/php5-fpm", "-F"]
