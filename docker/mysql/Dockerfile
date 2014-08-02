FROM pmt-base

RUN apt-get install -y mysql-server

ADD entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 3306

CMD ["/usr/sbin/mysqld", "--datadir=/var/lib/mysql"]