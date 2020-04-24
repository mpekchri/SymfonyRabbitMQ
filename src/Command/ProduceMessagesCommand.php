<?php

namespace App\Command;

use App\Service\RequestHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;


class ProduceMessagesCommand extends Command
{
    // Defines the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:produce-messages';

    /**
      * @var RequestHandler
      */
    private $reqHandler;

    /**
      * @var MessageBusInterface
      */
    private $bus;


    public function __construct(RequestHandler $reqHandler, MessageBusInterface $bus)
    {
        // best practices recommend to call the parent constructor first and
        // then set your own properties. That wouldn't work in this case
        // because configure() needs the properties set in this constructor
        $this->reqHandler = $reqHandler;
        $this->bus = $bus;

        parent::__construct();
    }


    protected function configure()
    {
      // Defines that app:produce-messages may take an argument named 'messages'
      $this->
          addOption(
              'messages',
              null,
              InputOption::VALUE_OPTIONAL,
              'How many messages should be produced',
              20
          );
    }

    /**
     * Defines the code that will be executed each time we run this command.
     * 
     * Command's logic is identical to BasicController:
     * Loop until you reach $input->getOption('messages') times of iterations. 
     * In each iteration reqHandler: RequestHandler is used to receive data from the 3rd Party API.
     * Then, those data are used in order to create a message: MyMessage and a rootingKey: string.
     * Finally a bus: MessageBusInterface is used to forward the message to the queue.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Producing messages ');

        for($i=0; $i<$input->getOption('messages'); $i++){
            $deserializedResponse = $this->reqHandler->sendRequest();
            // extracts two variables: $message and $rootingKey
            extract($deserializedResponse);
    
            $messages[] = $message;
    
            // dispatch message (send to bus), including rooting key
            $this->bus->dispatch($message,[
              new AmqpStamp($rootingKey)
            ]);
            $output->write('.');
        }

        $output->writeln('');
        $output->write('Producing messages finished. A total of '.($input->getOption('messages')).' messages have been sent to the queue.
        ');
        return 0;
    }

}