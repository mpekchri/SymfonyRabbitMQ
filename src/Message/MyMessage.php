<?php

namespace App\Message;

class MyMessage
{
  /**
    * @var int
    */
  private $value, $timestamp;

  public function __construct($data){
    $this->value = $data['value'];
    $this->timestamp = $data['timestamp'];
  }

  public function getValue() : int
  {
    return $this->value;
  }

  public function getTimestamp() : int
  {
    return $this->timestamp;
  }

  public function setValue(int $value)
  {
    return $this->value = $value;
  }

  public function setTimestamp(int $timestamp)
  {
    return $this->timestamp = $timestamp;
  }
}
