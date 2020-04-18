<?php

namespace App\Serializer;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use App\Message\MyMessage;

class ExternalJsonSerializer implements SerializerInterface
{

  /**
    * Each time a worker consumes a message from the queue,
    * the transport calls decode() on its serializer.
    *
    * Since we define a custom decode(), we should convert
    * the argument $encodedEnvelope into a Envelope instance.
    * Meanwhile, we are able to handle
    * the third party data contained in $encodedEnvelope['body'] as we wish.
    *
    * TODO : add exception handling & json validation.
    * Current version of decode() is propably going to fail, without
    * these implementations.
    */
  public function decode(array $encodedEnvelope): Envelope
  {

    $body = $encodedEnvelope['body'];
    $headers = $encodedEnvelope['headers'];

    $data = json_decode($body, true);
    $message = new MyMessage($data);

    // in case of redelivery, unserialize any stamps
    $stamps = [];
    if (isset($headers['stamps'])) {
        $stamps = unserialize($headers['stamps']);
    }
    return new Envelope($message, $stamps);
  }

  /**
    * Each time we send a message through a transport,
    * the encode() invoked.
    * Encode is responsible for serializing the data,
    * that the will be sent to the queue.
    *
    * A custom implementation of encode() is usually not necessary.
    * If we define a custom decode(), we should also define a custom encode(),
    * because this version will be used for re-serializing
    * the data contained in the failed messages.\
    * Each time the transport tries to re-sent a failed message, our custom
    * encode() is invoked.
    *
    * TODO : add exception handling
    * (different exception cases can be found in the
    * link: https://gist.github.com/mpiot/31774997f667ca7e3c69e65d8981130a ).
    */
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
