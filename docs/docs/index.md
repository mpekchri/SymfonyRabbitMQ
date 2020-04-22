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

## Run the application
Using docker is really easy to start a producer and a consumer service. Open a terminal
and cd in the project folder (where the Dockerfile-producer & Dockerfile-consumer exist).
Start by building the necessary images (skip if already done):

    docker image build -t chris-consumer-img -f Dockerfile-consumer .
    docker image build -t chris-producer-img -f Dockerfile-producer .

Then start two containers in the background:

    docker run -d -p 8010:8000 chris-consumer-img:latest
    docker run -d -p 8001:8000 chris-producer-img:latest

Great! Both worker that consumes messages and a producer has start. The producer
exposes an endpoint, where you may use in order to sent messages to the queue.  
Open a browser and head to `localhost:8001/basic` in order to send a single message
to the queue, or head to `localhost:8001/basic/{num: int}` in order to send multiple messages.
A valid url would be `localhost:8001/basic/10` and visiting it would result 10 messages to
be produced and sent to the queue. Queue will accept some of them, based on the rooting key used.  
Each time new messages arrive at it, the consumer consumes it and saves it to a database.

<!-- <div style="background:#FFD6B0;border-radius:2px;padding:10px 10px; ">
Warning: A consumer container may to fail after some amount of time. Since we are not
using a container orchestration tool, user is responsible for manually restarting it.
</div>  
<br/> -->
!!! warning
    A consumer container may to fail after some amount of time. Since we are not
    using a container orchestration tool, user is responsible for manually restarting it.

For more information about using the application it is strongly recommended to
visit the [Using The Application](using.md) section.

## Documentation
Documentation for this project can be found at ... TO-DO ...
