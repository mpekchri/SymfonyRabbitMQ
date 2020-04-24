<?php

namespace App\Serializer;


use App\Message\MyMessage;


class RequestSerializer
{
  public function __construct(){}

  /**
   * This function is used in order to serialize MyMessage objects into json data.
   * 
   * NOTE: Since there is no such need,
   * this function is not implemented and thus, not used in this application.
   */
  public function serialize($obj){
    throw new \BadMethodCallException("This function is not implemented. \
      No reason to use it, in this application, check for logical errors.
    ");
  }

  /**
   * This function is used in order to de-serialize json data,
   * which have been received as a response to a 3rd party API.
   * The request to the specific API and thus, the response data are provided
   * by an instance type of RequestHandler.
   * 
   * The deserialize function receives the response's data
   * and returns the corresponding message: MyMessage and rootingKey: string.
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


  /**
   * The hex2dec_string() function is used by deserialize(),
   * in order to convert the hexadecimal representation of a string
   * to its corresponding decimal representation.
   * 
   * e.g. the hex2dec_string('84df0c0087b94ebb') will return '9574384528092672000'
   */
  private function hex2dec_string(string $hex) :string
  {
    $num = hexdec($hex);
    return number_format($num, 0, '', '');
  }
}
