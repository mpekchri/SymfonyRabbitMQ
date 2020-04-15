<?php

namespace App\Serializer;


class RequestSerializer
{
  public function __construct(){

  }

  public function serialize($obj){

  }

  public function deserialize($obj){
    $data = json_decode($obj, true);

    return $data;
  }
}
