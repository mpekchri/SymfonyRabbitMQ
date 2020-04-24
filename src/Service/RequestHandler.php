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
   * Sends a single request and receives the response.
   * Then, it forwards the response data to a RequestSerializer,
   * in order to de-serialize them into a message: MyMessage and a rootingKey: string.
   * It returns the result produced by this de-serialization.
   */
  public function sendRequest(){
    $response = file_get_contents($this->url, false, $this->context);
    return $response !== FALSE ? $this->serializer->deserialize($response) : null;
  }

}
