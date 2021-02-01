FROM varnish:6.5.1-1

ARG APACHE_HOST

COPY ./config/default.vcl /etc/varnish/

RUN sed -i -r "s/APACHE_HOST/${APACHE_HOST}/g" /etc/varnish/default.vcl
