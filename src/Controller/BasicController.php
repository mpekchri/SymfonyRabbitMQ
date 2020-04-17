<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RequestHandler;
use App\Message\MyMessage;
use Symfony\Component\Messenger\MessageBusInterface;


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

      $message = new MyMessage();
      for($i=0; $i<10; $i++){
        $this->bus->dispatch($message);
      }

      // // send requests & receive results
      // // TO-DO : make it async.
      // $results = $this->reqHandler->makeRequests($num);
      //
      // // TO-DO : send data to rabbitmq
      //
      // // TO-DO : consume data from rabbitmq
      //
      // // TO-DO : save data to database
      //
      // return $this->json([
      //     'result' => $results,
      // ]);
      return $this->json([
        'lela?' => 'fysika'
      ]);
    }
}
