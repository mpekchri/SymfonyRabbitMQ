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

* `Docker`{style="color:#EA6113;"} version 19.03.5 or higher

## Running the application
Using docker is really easy to start a producer and a consumer service. Open a terminal
and cd in the project folder (where the Dockerfile-producer & Dockerfile-consumer exist).
Start by building the necessary images (skip if already done):

    docker image build -t chris-consumer-img -f Dockerfile-consumer .
    docker image build -t chris-producer-img -f Dockerfile-producer .

Then start two containers in the background:

    docker run --restart=always -d -p 8010:8000 chris-consumer-img:latest
    docker run -d -p 8001:8000 chris-producer-img:latest

Great! Both worker that consumes messages and a producer has start. The producer
exposes an endpoint, where you may use in order to sent messages to the queue.  
Open a browser and head to `localhost:8001/basic`{style="color:#EA6113;"} in order to send a single message
to the queue, or head to `localhost:8001/basic/{num: int}`{style="color:#EA6113;"} in order to send multiple messages.
A valid url would be `localhost:8001/basic/10`{style="color:#EA6113;"} and visiting it would result 10 messages to
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
    In order to avoid such situations, the --restart=always argument is provided to the docker run
    command that starts the consumer's container.

!!! error "Important"
    This application is only a demo for demonstration purposes. It is not configured for production.

<!-- For more information about using the application it is strongly recommended to
visit the [Using The Application](using.md) section. -->

## Documentation
Documentation files. which are used to build the current site, can be found at this [github repository](https://github.com/mpekchri/SymfonyRabbitMQ) under the docs/ directory. If you wish to edit the docs, cd into the docs/ directory and run:

    make serve-docs

This command will start a local development server, which can be used to render the edited docs, in realtime.
If you wish to build the docs into a static website, ready for deployment in heroku, just run:

    make build-docs

This command will create a new folder, named sites/, under the docs/ directory. You may use the following command
in order to deploy to heroku, or deploy to another provider.

    git push heroku `git subtree split --prefix docs/site master`:master --force


!!! warning
    In order to start the development server, or build the docs both `python 3` and `pipenv` must
    be installed in your system.