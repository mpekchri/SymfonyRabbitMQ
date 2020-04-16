<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RequestHandler;
use App\Producer\MessageProducer;
use App\Consumer\MessageConsumer;


/**
  * A Controller which provides handler functions
  * for endpoints specified with a Route annotation
  */
class BasicController extends AbstractController
{
    /**
      * @var RequestHandler
      */
    private $reqHandler;
    /**
      * @var MessageProducer
      */
    private $producer;
    /**
      * @var MessageConsumer
      */
    private $consumer;

    public function __construct(RequestHandler $reqHandler,
      MessageProducer $producer, MessageConsumer $consumer
    )
    {
      $this->reqHandler = $reqHandler;
      $this->producer = $producer;
      $this->consumer = $consumer;
    }

    /**
     * @Route("/publisher", name="api.publish.single")
     * @Route("/publisher/{num}", name="api.publish.multiple")
     */
    public function publish(int $num = 1)
    {
      // send requests & receive results
      // TO-DO : make it async.
      $results = $this->reqHandler->makeRequests($num);

      // TO-DO : send data to rabbitmq
      $this->producer->publishMultipleMessage($results);

      return $this->json($results);
    }


    /**
     * @Route("/consumer", name="api.consume")
     */
    public function consume(int $num = 1)
    {
      // TO-DO : consume data from rabbitmq
      $this->consumer->consumeMessages();

      // TO-DO : save data to database

      return $this->json([
          'result' => 'consumer finished',
      ]);
    }
}
