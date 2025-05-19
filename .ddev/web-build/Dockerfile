ARG BASE_IMAGE
FROM $BASE_IMAGE
COPY *.crt /usr/local/share/ca-certificates/
RUN update-ca-certificates --fresh