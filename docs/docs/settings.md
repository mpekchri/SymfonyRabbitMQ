# Configuring Symfony with RabbitMQ & Doctrine

This section describes the necessary configuration of a Symfony 4 project,
in order to use it along with a RabbitMQ queue and an Object relational mapper (ORM),
known as Doctrine.

## Symfony 4 Project Structure
When building a new Symfony 4 project using the command

    symfony new <project_name>

a folder named <project_name> is created, having the project structure as shown
in the following figure:  
![](img/img1.png){: style="height:270px;width:150px;margin-left:2%; margin-top:10px"}

In order to combine with RabbitMQ we use the Symfony Messenger Component.  
Messenger provides a message bus with the ability to send messages and
then handle them immediately in your application or send them through transports (e.g. queues) to be handled later.
Also, using the Doctrine ORM requires the doctrine-orm bundle.  
The following commands may be used to install the necessary bundles:

    composer require symfony/messenger
    composer require symfony/orm-pack

After installing those bundles, the project structure includes some extra files. In the figure below
part of the project structure is shown, where we highlight (with green color), the files that must be
configured.  
![](img/img2.png){: style="height:270px;width:150px;margin-left:2%; margin-top:10px"}

## Configuring the .env file
Communication with the RabbitMQ queue should be done using the following credentials:

* `hostname:`{style="color:#EA6113;"} candidatemq.n2g-dev.net
* `username:`{style="color:#EA6113;"} cand_d4uf
* `password:`{style="color:#EA6113;"} Yd6bCNQgWpx429zr
* `exchange:`{style="color:#EA6113;"} cand_d4uf
* `queue:`{style="color:#EA6113;"} cand_d4uf_results  

Therefore, our .env file should include the following lines:

    ###> symfony/messenger ###
    MESSENGER_TRANSPORT_DSN=amqp://cand_d4uf:Yd6bCNQgWpx429zr@candidatemq.n2g-dev.net
    ###< symfony/messenger ###

## Configuring the messenger.yaml file
This is a file that must be configured when using Symfony's Messenger to exchange messages with RabbitMQ.
The figure below displays the big picture of message exchange between a Symfony application and a RabbitMQ queue:

![](img/img3.png){: style="height:20%;width:80%;margin-left:5%"}

A message Producer sends the message to an exchange, along with a rooting key. An exchange
is connected to one or more queues, using a binding and a binding key. When a message arrives at
the exchange, it compares the rooting key with each binding key and forwards the message to the
corresponding queue. The way an exchange compares a rooting key with a binding key depends on the
type of the exchange (e.g. direct). If messages exist in a queue, they may be consumed by one or more consumers.

When using the Symfony Messenger Component a message bus is provided. Using it, we have the ability
to send messages and then handle them immediately in our application or send them
through transports (e.g. queues) to be handled later.  
Messenger supports a number of different transport types, each with their own options. Since
we need to use RabbitMQ we need to configure the AMQP Transport. In order to do so, we
configure the messenger.yaml as shown below:

    framework:
        messenger:
            # Uncomment this (and the failed transport below) to send failed messages
            # to this transport for later handling.
            # failure_transport: failed

            transports:
                # https://symfony.com/doc/current/messenger.html#transport-configuration
                async:
                  dsn: '%env(MESSENGER_TRANSPORT_DSN)%'
                  options:
                    auto_setup: false
                    # for AMQP send to a separate exchange then queue
                    exchange:
                      name: cand_d4uf
                      type: direct

                consumer_transport:
                  dsn: '%env(MESSENGER_TRANSPORT_DSN)%'
                  options:
                    auto_setup: false
                    queues:
                      cand_d4uf_results: ~
                  # -> If you wish to use custom serializer for this transport,
                  # uncomment the next line:
                  # serializer: App\Serializer\ExternalJsonSerializer

            routing:
              # Route your messages to the transports
              'App\Message\MyMessage' : [async, consumer_transport]
              # -> If you wish to use custom serializer for this transport,
              # remove the 'consumer_transport' from the array above.

There we define two Transports: async and consumer_transport. Both transports need credentials
in order to connect to the queue, which have been declared in the .env file. These credentials
are accessible through the dsn option. Additionally, the async transport (used for producing messages)
need to know about the exchange, while the consumer_transport (used for consuming messages)
only need to know the queue name. The option auto_setup must be set to false, in order to work with
a queue already configured by a third party.
<!-- <div style="background:#C8E4FF;border-radius:2px;padding:10px 10px; ">
Using different transports we are able to define custom serializer for messages that will be published
or consumed. We need to define custom serializer if we consume messages from the queue, which are created
by a third party.
</div>   -->
!!! note
    Using different transports we are able to define custom serializer for messages that will be published
    or consumed. We need to define custom serializer if we consume messages from the queue, which are created
    by a third party.

## Configuring the services.yaml file
With Doctrine, saving entities to the database is easy, since the Doctrine's object manager works on our behalf.
But in order to use it, we need to declare it as a service, so it can be autowired by Symfony. Thus, our services.yaml
file should include the following lines:

    # add more service definitions when explicit configuration is needed
    Doctrine\Persistence\ObjectManager: '@doctrine.orm.default_entity_manager'
