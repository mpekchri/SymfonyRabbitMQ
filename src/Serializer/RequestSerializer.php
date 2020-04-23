<?php

namespace App\Serializer;


use App\Message\MyMessage;


class RequestSerializer
{
  public function __construct(){

  }

  /*
   * TODO: Add description.
   */ 
  public function serialize($obj){
    throw new BadMethodCallException("This function is not implemented. \
      No reason to use it, in this application, check for logical errors.
    ");
  }

  /*
   * TODO: Add description.
   */ 
  public function deserialize($obj){
    $data = json_decode($obj, true);
    $data['gatewayEui'] = $this->hex2dec_string($data['gatewayEui']);
    $data['profileId'] = $this->hex2dec_string($data['profileId']);
    $data['endpointId'] = $this->hex2dec_string($data['endpointId']);
    $data['clusterId'] = $this->hex2dec_string($data['clusterId']);
    $data['attributeId'] = $this->hex2dec_string($data['attributeId']);

    $message_key = $data['gatewayEui'].".".$data['profileId'].
      ".".$data['endpointId'].".".$data['clusterId']."."
      .$data['attributeId'];

    $message_body = [
      "value" => $data['value'],
      "timestamp" => $data['timestamp'],
      "rootingKey" => $message_key
    ];

    $message = new MyMessage($message_body);


    return [
      'message' => $message,
      'rootingKey' => $message_key
    ];
  }


  /*
   * TODO: Add description.
   */ 
  private function hex2dec_string(string $hex) :string
  {
    $num = hexdec($hex);
    return $string = number_format($num, 0, '', '');
  }
}
