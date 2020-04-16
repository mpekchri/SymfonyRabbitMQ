<?php

namespace App\Producer;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessageProducer
{
  /**
    * @var string
    */
  private $host, $user, $pass, $exchange, $queue, $vhost;

  /**
    * @var int
    */
  private $port;

  public function __construct(){
    $this->host = 'candidatemq.n2g-dev.net';
    $this->user = 'cand_d4uf';
    $this->pass = 'Yd6bCNQgWpx429zr';
    $this->exchange = 'cand_d4uf';
    $this->queue = 'cand_d4uf_results';
    $this->vhost = '/';
    $this->port = 5672;
  }

  // /**
  //   * Creates connection and publishes a single message.
  //   * TO-DO : Delete it, if it's not useful (since we got publishMultipleMessage).
  //   */
  // public function publishSingleMessage($message, $rootingKey){
  //   $serializedMessage = json_encode($message);
  //
  //   $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass);
  //   $channel = $connection->channel();
  //   $msg = new AMQPMessage($serializedMessage,[
  //     'content_type' => 'application/json'
  //   ]);
  //   $channel->basic_publish($msg, $this->exchange, $rootingKey);
  //   $channel->close();
  //   $connection->close();
  // }

  /**
    * Creates connection and publishes a single message.
    * TO-DO : Maybe use the publish_batch() ?
    * Check the link: https://github.com/php-amqplib/php-amqplib for more info.
    */
  public function publishMultipleMessage($multipleData){
    $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass);
    $channel = $connection->channel();

    foreach($multipleData as $data){
      $message = $data['body'];
      $rootingKey = $data['key'];
      $serializedMessage = json_encode($message);

      $msg = new AMQPMessage($serializedMessage,[
        'content_type' => 'application/json'
      ]);
      $channel->basic_publish($msg, $this->exchange, $rootingKey);
    }

    $channel->close();
    $connection->close();
  }

}
