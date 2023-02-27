FROM nginx
ENV TZ=UTC

RUN apt-get update
RUN apt-get install mc -y

COPY ./certs /certs
COPY ./nginx/ /etc/nginx/conf.d/
