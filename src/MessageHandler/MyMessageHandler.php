<?php

namespace App\MessageHandler;

use App\Entity\Net2Grid;
use App\Message\MyMessage;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class MyMessageHandler implements MessageHandlerInterface
{

  /**
   * @var ObjectManager
   */
  private $manager;

  public function __construct(ObjectManager $manager)
  {
    $this->manager = $manager;
  }

  public function __invoke(MyMessage $msg)
  {
    // \sleep(6);
    // dump($msg);
    $rootingKey = $msg->getRootingKey();
    $keyInfo = explode('.', $rootingKey);

    $net2grid = new Net2Grid();
    $net2grid->setValue($msg->getValue());
    $net2grid->setTimestamp($msg->getTimestamp());
    $net2grid->setGatewaiUi($keyInfo[0]);
    $net2grid->setProfile($keyInfo[1]);
    $net2grid->setEndpoint($keyInfo[2]);
    $net2grid->setCluster($keyInfo[3]);
    $net2grid->setAttribute($keyInfo[4]);
    dump($net2grid);

    // $this->manager->persist($net2grid);
    // $this->manager->flush();
  }

}
