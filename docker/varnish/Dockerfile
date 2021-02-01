FROM varnish:6.5.1-1

ARG APP_DOMAIN

COPY ./config/default.vcl /etc/varnish/

RUN sed -i -r "s/APP_DOMAIN/${APP_DOMAIN}/g" /etc/varnish/default.vcl
