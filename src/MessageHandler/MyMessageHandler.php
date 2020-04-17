<?php

namespace App\MessageHandler;

use App\Message\MyMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class MyMessageHandler implements MessageHandlerInterface
{

  public function __invoke(MyMessage $msg)
  {
    // \sleep(6);
    dump($msg);
  }

}
