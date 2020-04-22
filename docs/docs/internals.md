# The Big Picture
This section provides an extensive look at application's classes
and how these are combined together to provide the desired functionality.

## Project Structure
The figure bellow shows part of the project structure, which contains several new classes.
Each class has a specific responsibility, e.g. consuming data from an API, serializing the data,
creating messages, etc. More details are provided later in this section.

![](img/img4.png){: style="height:10%;width:30%;margin-left:5%"}

## Producing Messages
In order to produce and publish messages to the queue, the following process is necessary.

![](img/img5.png){: style="height:10%;width:90%; margin-left:1%"}

Several classes are involved in the process:

### BasicController  
Exposes an endpoint at `/basic/{num_of_messages: int}`{style="color:#EA6113;"}. Each time a request to this endpoint occurs, Symfony
makes sure that BasicController.index() is invoked. There, the controller uses the <span style="color:#EE9B4E">requestHandler-></span><span style="color:#4EA1EE">sendRequest()</span> function, in order to receive a message (type of array) and a rooting key (type of string). The requestHandler is a `RequestHandler`{style="color:#EA6113;"} instance. Then, controller uses the function <span style="color:#4EA1EE">dispatch()</span>, provided by Symfony Messernger Component, in order to sent the message to the queue.


### RequestHandler  
Exposes a public method named <span style="color:#4EA1EE">sendRequest</span>. Each time <span style="color:#4EA1EE">sendRequest()</span> is invoked, a `GET`{style="color:#EA6113;"} request is sent at `https://a831bqiv1d.execute-api.eu-west-1.amazonaws.com/dev/results`{style="color:#EA6113;"}. The response is forwarded to a `RequestSerializer`{style="color:#EA6113;"} instance, in order to construct
a message object, type of `MyMessage`{style="color:#EA6113;"} and a rooting key, type of string. Both the message and the rooting key are
returned as function's result, to `BasicController`{style="color:#EA6113;"}.


### RequestSerializer
Is responsible for de-serialization of responses, received by `RequestHandler`{style="color:#EA6113;"}. Each response
has the following format:

    {
        gatewayEui:     string,
        profileId:      string,
        endpointId:     string,
        clusterId:      string,
        attributeId:    string,
        value:          int,
        timestamp:      int        
    }

The `RequestSerializer`{style="color:#EA6113;"} will turn such a response into a `message: MyMessage`{style="color:#EA6113;"} instance and a `rooting key: string`{style="color:#EA6113;"}.  
The rooting key has the form: `<gateway eui>.<profile>.<endpoint>.<cluster>.<attribute>`{style="color:#EA6113;"} and is created by the decimal value of the corresponding values in respone object. The convertion from string to decimal is achieved using the
php build-in function <span style="color:#4EA1EE">hex2dec_string</span>.


### MyMessage
A common php class which defines the data that each queue message contains.
MyMessage has the following attributes:

* value `int`{style="color:#EA6113;"}
* timestamp  `int`{style="color:#EA6113;"}
* rootingKey  `string`{style="color:#EA6113;"}

In our messenger.yaml file, we have already defined that `MyMessage`{style="color:#EA6113;"} class will be used for rooting by
our tranports. Thus, Symfony Component Messenger knows that it should sent a `MyMessage`{style="color:#EA6113;"} object
to the queue. Additionally, when consuming a message type of `MyMessage`{style="color:#EA6113;"}, it tries to invoke
a proper handler function.

## Consuming Messages
In order to consume messages from the queue we need a properly defined class. This class should
implement the `Symfony\Component\Messenger\Handler\MessageHandlerInterface`{style="color:#EA6113;"}. In this application the
corresponding class is named `MyMessageHandler`{style="color:#EA6113;"}.

![](img/img6.png){: style="height:10%;width:95%; margin-left:1%"}

### MyMessageHandler
A class that implements `Symfony\Component\Messenger\Handler\MessageHandlerInterface`{style="color:#EA6113;"}.
Here we override the <span style="color:#4EA1EE">\__invoke(</span><span style="color:#7B0DAE">MyMessage</span> <span style="color:#EE9B4E">$msg</span><span style="color:#4EAEEE">)</span>, in order to define how to handle
a message each time it is consumed. In our cases, a consumed message's attributes are used in order to create a `Net2Grid`{style="color:#EA6113;"} object, which allows us to store those data in the database.

<!-- <div style="background:#C8E4FF;border-radius:2px;padding:10px 10px; ">
Note that all we need to do, is declare that the <span style="color:#0E78DA">\__invoke</span> function accepts as argument a </span><span style="color:#7B0DAE">MyMessage</span> object. This way the Symfony Messenger Component knows to use this specific handler each time a message arrives in the Bus, in order to be consumed.
</div> <br/> -->
!!! note
    Note that all we need to do, is declare that the <span style="color:#0E78DA">\__invoke</span> function accepts as argument a </span>`MyMessage`{style="color:#7B0DAE;"} object. This way the Symfony Messenger Component knows to use this specific handler each time a message arrives in the Bus, in order to be consumed.

### Net2Grid
This is a doctrine ORM Entity used to easily save data to the database. The Entity
has the following attributes:

* id        `ID`{style="color:#EA6113;"}
* gatewaiUi `STRING`{style="color:#EA6113;"}
* profile   `STRING`{style="color:#EA6113;"}
* endpoint  `STRING`{style="color:#EA6113;"}
* cluster   `STRING`{style="color:#EA6113;"}
* attribute `STRING`{style="color:#EA6113;"}
* value     `BIGINT`{style="color:#EA6113;"}
* timestamp `BIGINT`{style="color:#EA6113;"}


## Declaring Additional Services
Since `BasicController`{style="color:#EA6113;"} uses an instance type of`RequestHandler`{style="color:#EA6113;"} and `RequestHandler`{style="color:#EA6113;"}
uses an instance type of `RequestSerializer`{style="color:#EA6113;"}, we should add the following lines in
our services.yaml file.

    # add more service definitions when explicit configuration is needed
    App\Controller\BasicController: ['@App\Service\RequestHandler']
    App\Service\RequestHandler: ['@App\Serializer\RequestSerializer']
