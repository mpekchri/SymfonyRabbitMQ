FROM ubuntu:16.04 as base
LABEL maintainer="christopher bekos"

RUN python --version

# # Install PHP 7.4
# RUN sudo apt-get install software-properties-common
# RUN sudo add-apt-repository ppa:ondrej/php
# RUN sudo apt-get update
# RUN sudo apt-get install -y php7.4

# # Install php-amqp
# RUN sudo apt-get install php-amqp

# # Install Composer
# RUN sudo apt-get install curl php-cli php-mbstring git unzip
# RUN sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# # Ready to Go
# WORKDIR /app
# COPY . ./
# RUN php composer.phar install
