FROM docker.elastic.co/elasticsearch/elasticsearch:7.10.1

ARG ELASTICSEARCH_HOST

COPY ./config/elasticsearch.yml /usr/share/elasticsearch/config/elasticsearch.yml

RUN sed -i -r "s/ELASTICSEARCH_HOST/${ELASTICSEARCH_HOST}/g" /usr/share/elasticsearch/config/elasticsearch.yml
