<?php

namespace App\Serializer;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use App\Message\MyMessage;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ExternalJsonSerializer implements SerializerInterface
{

  public function decode(array $encodedEnvelope): Envelope
  {

    $body = $encodedEnvelope['body'];
    $headers = $encodedEnvelope['headers'];

    // $data = json_decode($body, true);
    // $message = new MyMessage($data);

    // dump('====================== \n');
    // dump( json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $body), true ) );
    // // dump(($body));
    // dump('====================== \n');

    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $message = new MyMessage([
      'value' => 0,
      'timestamp' => 0
    ]);
    $serializer->deserialize($body, MyMessage::class, 'json');




    // in case of redelivery, unserialize any stamps
    $stamps = [];
    if (isset($headers['stamps'])) {
        $stamps = unserialize($headers['stamps']);
    }
    return new Envelope($message, $stamps);
  }


  public function encode(Envelope $envelope): array
  {
    // this is called if a message is redelivered for "retry"
    $message = $envelope->getMessage();
    if ($message instanceof MyMessage) {
        // recreate what the data originally looked like
        $data = [
          'value' => $message->getValue(),
          'timestamp' => $message->getTimestamp()
        ];
    } else {
        throw new \Exception('Unsupported message class');
    }
    $allStamps = [];
    foreach ($envelope->all() as $stamps) {
        $allStamps = array_merge($allStamps, $stamps);
    }
    return [
        'body' => json_encode($data),
        'headers' => [
            // store stamps as a header - to be read in decode()
            'stamps' => serialize($allStamps)
        ],
    ];
  }

}
