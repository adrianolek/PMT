FROM pmt-base

RUN apt-get install -y nginx

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]

CMD ["/usr/sbin/nginx"]