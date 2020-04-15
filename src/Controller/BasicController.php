<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RequestHandler;


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

    public function __construct(RequestHandler $reqHandler){
      $this->reqHandler = $reqHandler;
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

      // TO-DO : serialize results

      // TO-DO : send data to rabbitmq

      // TO-DO : consume data from rabbitmq

      // TO-DO : save data to database

      return $this->json([
          'result' => $results,
      ]);
    }
}
