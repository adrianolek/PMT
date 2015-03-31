FROM stackbrew/ubuntu:14.04

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -y; \
  apt-get install -y nginx; \
  apt-get clean; \
  rm -rf /var/lib/apt/lists/*

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]

CMD ["/usr/sbin/nginx"]
