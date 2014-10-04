FROM pmt-base

RUN apt-get install -y php5-cli php5-intl npm ant curl git
RUN npm install -g uglify-js uglifycss
RUN ln -s /usr/bin/nodejs /usr/bin/node

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www
ENTRYPOINT ["/usr/bin/ant"]

CMD ["prod"]