#!/bin/bash

if [ ! -d /var/lib/mysql/mysql ]; then
	mysql_install_db --datadir=/var/lib/mysql
	
	if [ -f /init.sql ]; then
    exec /usr/sbin/mysqld --datadir=/var/lib/mysql --init-file=/init.sql
	fi
fi

exec "$@"