# Symfony 4 & Rabbitmq

A minimal project combining Symfony 4 and Rabbitmq.

## The Big Picture
This application uses the Symfony 4 framework in order to exchange data with
a message broker.
The message broker is implemented by a third party, using RabbitMQ
and credentials are provided in order to publish/consume messages to/from it.
Data which are included in each message, are provided by a third party API.

The Symfony Messenger Component is used in order to send and consume
messages to and from rabbitmq. Each time a message is consumed a new doctrine Entity,
type of Net2Grid, is created parameterized according to message contents.
Finally, the new entity is used, in order to save this information in a mysql database.

## Requirements
In order to install this application, your system must fulfill these requirements:

* `Docker` version 19.03.5 or higher
* `Docker Compose` 1.25.4 or higher

## Run the application
Make sure you are in the directory that contains the Dockerfile.  
Then, open a terminal and run:

    docker-compose up

## Documentation
Documentation for this application may be created using
code in this [repository](https://www.link.com).
