<?php

namespace App\Message;

class MyMessage
{
  /**
    * @var string
    */
  private $msg, $key;

  public function __construct($key, $body){
    $this->key = $key;
    $this->msg = $body;
  }

  public function getMessage(){
    return $this->msg;
  }

  public function getRootingKey(){
    return $this->key;
  }
}
