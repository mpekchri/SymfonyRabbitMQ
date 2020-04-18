<?php

namespace App\Message;

class MyMessage
{
  /**
    * @var int
    */
  private $value, $timestamp;
  /**
    * @var string
    */
  private $rootingKey;

  public function __construct($data){
    $this->value = $data['value'];
    $this->timestamp = $data['timestamp'];
    $this->rootingKey = $data['rootingKey'];
  }

  public function getValue() : int
  {
    return $this->value;
  }

  public function getTimestamp() : int
  {
    return $this->timestamp;
  }

  public function getRootingKey() : string
  {
    return $this->rootingKey;
  }

  public function setValue(int $value)
  {
    return $this->value = $value;
  }

  public function setTimestamp(int $timestamp)
  {
    return $this->timestamp = $timestamp;
  }

  public function setRootingKey(string $rootingKey)
  {
    return $this->rootingKey = $rootingKey;
  }
}
