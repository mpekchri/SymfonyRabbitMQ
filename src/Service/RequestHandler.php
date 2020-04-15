<?php

namespace App\Service;

use App\Serializer\RequestSerializer;

/**
  * A Service for sending requests and receiving responses at the given url.
  * It serves decoupling the request handling from controller logic.
  */
class RequestHandler
{
  /**
    * @var RequestSerializer
    */
  private $serializer;

  public function __construct(RequestSerializer $serializer){
    $this->serializer = $serializer;
    $this->url = 'https://a831bqiv1d.execute-api.eu-west-1.amazonaws.com/dev/results';
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'GET',
        )
    );
    $this->context  = stream_context_create($options);
  }

  /**
    * Private function that sends a single request,
    * receives response and convert it's data to json object, if valid.
    * TO-DO : use curl to make it asynchronous.
    */
  private function sendSingleRequest(){
    $result = file_get_contents($this->url, false, $this->context);
    // return $result !== FALSE ? json_decode($result, true) : null;
    return $result !== FALSE ? $this->serializer->deserialize($result) : null;
  }

  public function makeRequests(int $num_of_requests = 1){
    $results = [];
    for($i=0; $i<$num_of_requests; $i++){
      $results[$i] = $this->sendSingleRequest();
    }
    return $results;
  }

}
