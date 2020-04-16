<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RequestHandler;
use App\Service\MessageHandler;


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
      * @var MessageHandler
      */
    private $msgHandler;

    public function __construct(RequestHandler $reqHandler, MessageHandler $msgHandler){
      $this->reqHandler = $reqHandler;
      $this->msgHandler = $msgHandler;
    }

    /**
     * @Route("/basic", name="api.request.single")
     * @Route("/basic/{num}", name="api.request.multiple")
     */
    public function index(int $num = 1)
    {
      // send requests & receive results
      // TO-DO : make it async.
      $results = $this->reqHandler->makeRequests($num);
      // DONE? : send data to rabbitmq
      foreach($results as $msg_data){
        $this->msgHandler->publishMessage($msg_data);
      }

      // TO-DO : consume data from rabbitmq - LOUL not here bro

      // TO-DO : save data to database - LOUL not here bro

      return $this->json([
          'result' => $results,
      ]);
    }
}
