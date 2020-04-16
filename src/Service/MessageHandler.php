<?php

namespace App\Service;

use App\Producer\MessageProducer;

class MessageHandler
{
  /**
    * @var MessageProducer
    */
  private $producer;

  public function __construct(MessageProducer $producer){
    $this->producer = $producer;
  }

  public function publishMessage($serialized_data)
  {
    $messageBody = $serialized_data['body'];
    $routingKey = $serialized_data['key'];
    // publish message to exchange
    $this->producer->setContentType('application/json');
    // $this->producer->publish($msgBody [, $routingKey, $additionalProperties, $headers])
    $this->producer->publish($messageBody, $routingKey);
  }



}
