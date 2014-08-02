FROM pmt-base

RUN apt-get install -y php5-fpm php5-cli php5-mysqlnd php5-intl mysql-client

EXPOSE 9000

CMD ["/usr/sbin/php5-fpm", "-F"]