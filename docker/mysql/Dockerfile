FROM stackbrew/ubuntu:14.04

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -y; \
  apt-get install -y mysql-server; \
  apt-get clean; \
  rm -rf /var/lib/apt/lists/*

ADD entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 3306

CMD ["/usr/sbin/mysqld", "--datadir=/var/lib/mysql"]
