FROM debian:stretch-slim

ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get upgrade -y && apt-get install -y apache2 php php-xml php-mysql composer zip unzip git mariadb-client
RUN a2enmod rewrite
