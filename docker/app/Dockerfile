FROM thecodingmachine/php:7.1-v2-slim-apache

USER root

RUN apt-get update
RUN cd /usr/local/lib/thecodingmachine-php/extensions/current/bcmath/ && ./install.sh
RUN cd /usr/local/lib/thecodingmachine-php/extensions/current/gd/ && ./install.sh
RUN cd /usr/local/lib/thecodingmachine-php/extensions/current/intl/ && ./install.sh
RUN cd /usr/local/lib/thecodingmachine-php/extensions/current/mcrypt/ && ./install.sh
RUN cd /usr/local/lib/thecodingmachine-php/extensions/current/pdo_mysql/ && ./install.sh
RUN cd /usr/local/lib/thecodingmachine-php/extensions/current/soap/ && ./install.sh
RUN cd /usr/local/lib/thecodingmachine-php/extensions/current/xsl/ && ./install.sh
RUN cd /usr/local/lib/thecodingmachine-php/extensions/current/zip/ && ./install.sh
RUN cd /usr/local/lib/thecodingmachine-php/extensions/current/xdebug/ && ./install.sh

USER docker

ENV PHP_EXTENSION_BCMATH=1 \
    PHP_EXTENSION_GD=1 \
    PHP_EXTENSION_INTL=1 \
    PHP_EXTENSION_MCRYPT=1 \
    PHP_EXTENSION_PDO_MYSQL=1 \
    PHP_EXTENSION_SOAP=1 \
    PHP_EXTENSION_XSL=1 \
    PHP_EXTENSION_ZIP=1 \
    PHP_INI_MEMORY_LIMIT=2048M \
    PHP_INI_ERROR_REPORTING=E_ALL