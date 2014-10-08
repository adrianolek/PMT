FROM pmt-base

RUN apt-get install -y php5-fpm php5-cli php5-mysqlnd php5-intl php5-imagick php5-xdebug mysql-client

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]

CMD ["/usr/sbin/php5-fpm", "-F"]
