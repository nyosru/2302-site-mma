FROM stakater/base-alpine:3.5
ENV TZ=UTC

RUN apk --update add openssl

WORKDIR /certs

COPY ./docker/certs/generate-certs /usr/local/bin/generate-certs

CMD /usr/local/bin/generate-certs default mm.local

VOLUME /certs
