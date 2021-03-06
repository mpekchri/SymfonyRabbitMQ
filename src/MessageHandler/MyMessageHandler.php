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

  /**
   * MyMessageHandler is a class that implements Symfony\Component\Messenger\Handler\MessageHandlerInterface.
   * Here we override the __invoke(MyMessage $msg), in order to define how to handle a message 
   * each time it is consumed. 
   * In our case, a consumed message's attributes are used in order to create a Net2Grid object,
   * which allows us to store those data in the database.
   */ 
  public function __invoke(MyMessage $msg)
  {
    $rootingKey = $msg->getRootingKey();
    $keyInfo = explode('.', $rootingKey);

    $net2grid = new Net2Grid();
    $net2grid->setValue($msg->getValue());
    $net2grid->setTimestamp($msg->getTimestamp());
    $net2grid->setGatewayEui($keyInfo[0]);
    $net2grid->setProfile($keyInfo[1]);
    $net2grid->setEndpoint($keyInfo[2]);
    $net2grid->setCluster($keyInfo[3]);
    $net2grid->setAttribute($keyInfo[4]);
  
    // dump($msg);

    $this->manager->persist($net2grid);
    $this->manager->flush();
  }

}
