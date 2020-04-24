<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RequestHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;

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
      * @var MessageBusInterface
      */
    private $bus;


    public function __construct(RequestHandler $reqHandler, MessageBusInterface $bus){
      $this->reqHandler = $reqHandler;
      $this->bus = $bus;
    }

    /**
      * @Route("/basic", name="api.request.single")
      * @Route("/basic/{num}", name="api.request.multiple")
      * 
      * Loops until $num times of iterations are reached. 
      * In each iteration reqHandler: RequestHandler is used to receive data from the 3rd Party API.
      * Then, those data are used in order to create a message: MyMessage and a rootingKey: string.
      * Finally a bus: MessageBusInterface is used to forward the message to the queue.
      */
    public function index(int $num = 1)
    {
      $messages = [];

      for($i=0; $i<$num; $i++){
        $deserializedResponse = $this->reqHandler->sendRequest();
        // extracts two variables: $message and $rootingKey
        extract($deserializedResponse);
        $messages[] = $message;
        // dispatch message (send to bus), including rooting key
        $this->bus->dispatch($message,[
          new AmqpStamp($rootingKey)
        ]);
      }

      return $this->json([
        'messages_sent_to_queue' => $messages
      ]);
    }
}
