FROM ubuntu:16.04 as base
LABEL maintainer="christopher bekos"

# Configure sudo:
RUN apt-get update && \
      apt-get -y install sudo
RUN adduser --disabled-password --gecos '' docker
RUN adduser docker sudo
RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

USER docker
RUN sudo apt-get update 


# Install python & pipenv
RUN sudo apt-get update && sudo apt-get install -y software-properties-common
RUN sudo add-apt-repository ppa:deadsnakes/ppa
RUN sudo apt-get update 
RUN sudo apt-get install python3.6 -y

RUN sudo apt-get install python3-pip -y

RUN sudo pip3 install pipenv

# Install PHP 7.4
RUN sudo apt-get install -y language-pack-en-base
RUN sudo LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
RUN sudo apt-get update
RUN sudo apt-get install -y php7.4
# RUN php -v

# Install php-amqp
RUN sudo apt-get install php-amqp -y

# Install Composer
RUN sudo apt-get install curl php-cli php-mbstring git unzip -y
RUN curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
# RUN composer

RUN sudo apt install php-xml -y
RUN sudo apt-get install php7.4-curl -y

# # Ready to Go
# WORKDIR /app
# # WORKDIR /var/www/html/app
# COPY . ./
# RUN sudo composer install
# # RUN ls
# # -- NOT WORKING -- CMD symfony server:start --allow-http --no-tls --dir=./ --port=8000
# # CMD sudo php -S 0.0.0.0:8000 -t public/
# # CMD php bin/console messenger:consume consumer_transport


FROM base as dev-msg-consumer
WORKDIR /
COPY . /
RUN sudo composer install
EXPOSE 8081
# CMD php bin/console messenger:consume consumer_transport
CMD php bin/console messenger:consume consumer_transport 0.0.0.0:8081


FROM base as dev-msg-producer
WORKDIR /
COPY . /
RUN sudo composer install
EXPOSE 8000
CMD php -S 0.0.0.0:8000 -t public/

