<?php

namespace App\Consumer;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessageConsumer
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

  public function consumeMessages(){
    $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
    $channel = $connection->channel();

    $channel->basic_consume($this->queue, false, false, false, false, array($this, 'parseMessage'));
    // $channel->basic_consume($this->queue, false, false, false, false, 'lela' );

    // register_shutdown_function(array($this, 'shutdown'), $channel, $connection);

    while($channel->is_consuming()){
      $channel->wait();
    }

    $channel->close();
    $connection->close();
  }

  private function parseMessage(AMQPMessage $message){
    // it is configured to run each time a message arrives
    // handle message here -- json_decode($message->body)

    // echo json_decode($message->body);
    dump(json_decode($message->body));
  }

  // private function shutdown($channel, $connection){
  //   $channel->close();
  //   $connection->close();
  // }
}
