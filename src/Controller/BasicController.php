<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RequestHandler;
use App\Message\MyMessage;
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
     */
    public function index(int $num = 1)
    {
      $dummyLog = [];
      // send requests, receive results, construct messages & send them to rabbtimq
      for($i=0; $i<$num; $i++){
        $deserializedResponse = $this->reqHandler->sendRequest();
        $dummyLog[] = $deserializedResponse;
        $message = new MyMessage($deserializedResponse['body']);
        $rootingKey = $deserializedResponse['key'];
        $this->bus->dispatch($message,[
          new AmqpStamp($rootingKey)
        ]);
      }

      return $this->json([
        'http_response_contents' => $dummyLog
      ]);
    }
}
