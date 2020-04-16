<?php

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class MessageConsumer implements ConsumerInterface
{
  public function execute(AMQPMessage $msg)
  {
    $message = json_decode($msg->body, true);
    echo $message;
    return $message;
  }
}
