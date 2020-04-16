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
      "timestamp" => $data['timestamp']
    ];
    // dump($message_key);

    // $num = hexdec('84df0c0000102800');
    // $string = number_format($num, 0, '', '');
    // dump($string);
    // dump(dechex(9574384526953555968));
    // dump(dechex($num));

    return [
      'key' => $message_key,
      'body' => $message_body
    ];
  }

  private function hex2dec_string(string $hex) :string
  {
    $num = hexdec($hex);
    return $string = number_format($num, 0, '', '');
  }
}
