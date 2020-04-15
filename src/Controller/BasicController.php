<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RequestsHandler;


/**
  * A Controller which provides handler functions
  * for endpoints specified with a Route annotation
  */
class BasicController extends AbstractController
{
    /**
      * @var RequestsHandler
      */
    private $reqHandler;

    public function __construct(RequestsHandler $reqHandler){
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
