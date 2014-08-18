FROM pmt-base

RUN apt-get install -y php5-fpm php5-cli php5-mysqlnd php5-intl mysql-client npm
RUN npm install -g uglify-js uglifycss
RUN ln -s /usr/bin/nodejs /usr/bin/node

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]

CMD ["/usr/sbin/php5-fpm", "-F"]