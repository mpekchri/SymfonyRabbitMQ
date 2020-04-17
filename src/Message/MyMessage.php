<?php

namespace App\Message;

class MyMessage
{
  /**
    * @var string
    */
  private $msg;

  public function __construct(){
    $this->msg = 'Lelarxos Fytros';
  }

  public function getMessage(){
    return $this->msg;
  }
}
