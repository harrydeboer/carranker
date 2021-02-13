FROM redis:6.0.10
COPY ./config/redis.conf /usr/local/etc/redis/redis.conf
RUN mkdir -p /var/log/redis
RUN chown redis:redis -R /var/log/redis
CMD [ "redis-server", "/usr/local/etc/redis/redis.conf" ]
