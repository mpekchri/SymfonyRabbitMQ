<?php

namespace App\Service;

use App\Producer\MessageProducer;
// require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessageHandler
{
  /**
    * @var MessageProducer
    */
  private $producer;

  public function __construct(MessageProducer $producer){
    $this->producer = $producer;
  }

  public function publishMessage($deserialized_data)
  {
    $messageBody = $deserialized_data['body'];
    $routingKey = $deserialized_data['key'];

    // // publish message to exchange
    $this->producer->setContentType('application/json');
    // // $this->producer->publish($msgBody [, $routingKey, $additionalProperties, $headers])
    $this->producer->publish(json_encode($messageBody), array(
      'routingKey' => $routingKey
    ));


    // $connection = new AMQPStreamConnection('candidatemq.n2g-dev.net', 5672, 'cand_d4uf', 'Yd6bCNQgWpx429zr');
    // $channel = $connection->channel();
    // $msg = new AMQPMessage('Hello World!');
    // $channel->basic_publish($msg, 'cand_d4uf', $routingKey);
    // $channel->close();
    // $connection->close();

  }



}
