<?php

namespace App\Command;

use PhpAmqpLib\Exception\AMQPTimeoutException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

use App\Service\Characters\CharacterRabbitHandler;
use App\Service\FreeCompany\FreeCompanyRabbitHandler;
use App\Service\Linkshells\LinkshellRabbitHandler;
use App\Service\Rabbit\RabbitService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @package App\Command
 */
class AutoProcessCommand extends ContainerAwareCommand
{
    /** @var SymfonyStyle */
    private $io;
    /** @var RabbitService */
    private $rabbit;
    /** @var CharacterRabbitHandler */
    private $characterRabbitHandler;
    /** @var FreeCompanyRabbitHandler */
    private $freeCompanyRabbitHandler;
    /** @var LinkshellRabbitHandler */
    private $linkshellRabbitHandler;

    public function __construct(
        RabbitService $rabbit,
        CharacterRabbitHandler $characterRabbitHandler,
        FreeCompanyRabbitHandler $freeCompanyRabbitHandler,
        LinkshellRabbitHandler $linkshellRabbitHandler,
        $name = null
    ) {
        parent::__construct('app:auto');

        $this->rabbit = $rabbit;
        $this->characterRabbitHandler = $characterRabbitHandler;
        $this->freeCompanyRabbitHandler = $freeCompanyRabbitHandler;
        $this->linkshellRabbitHandler = $linkshellRabbitHandler;
    }

    /**
     * Configure search engine
     */
    protected function configure()
    {
        $this
            ->setName('app:auto')
            ->setDescription('Handle XIVSync data')
            ->setHelp('Migrates and process xivsync data from rabbit mq')
            ->addArgument('queue', InputArgument::REQUIRED, 'The Rabbit Message Queue to connect to')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);

        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('XIVSYNC');

        $queue = $input->getArgument('queue');

        $this->io->text([
            'Queue: '. $queue,
        ]);

        switch($queue) {
            default:
                $this->io->text("<error>Unknown queue: {$queue}</error>");
                break;

            case 'characters_add':
            case 'characters_update_1':
            case 'characters_update_2':
            case 'characters_update_3':
            case 'characters_update_4':
            case 'characters_update_5':
            case 'characters_achievements_1':
            case 'characters_achievements_2':
            case 'characters_achievements_3':
            case 'characters_achievements_4':
            case 'characters_achievements_5':
            case 'characters_friends_1':
            case 'characters_friends_2':
            case 'characters_friends_3':
            case 'characters_friends_4':
            case 'characters_friends_5':
                $this->io->text('Processing Characters');
                $handler = $this->characterRabbitHandler;
                break;
    
            case 'linkshells_add':
            case 'linkshells_update_1':
            case 'linkshells_update_2':
            case 'linkshells_update_3':
            case 'linkshells_update_4':
            case 'linkshells_update_5':
                $this->io->text('Processing Linkshells');
                $handler = $this->linkshellRabbitHandler;
                break;

            case 'freecompany_add':
            case 'freecompany_update_1':
            case 'freecompany_update_2':
            case 'freecompany_update_3':
            case 'freecompany_update_4':
            case 'freecompany_update_5':
                $this->io->text('Processing Free Companies');
                $handler = $this->freeCompanyRabbitHandler;
                break;
        }
        
        if (!isset($handler)) {
            $this->io->error('Finished due to no queue handler.');
            return;
        }
        
        // set symfony style
        $handler->setSymfonyStyle($input, $output);

        // connect to rabbit
        $this->io->text(['', 'Connecting to RabbitMQ: '. $queue]);
        $this->rabbit->connect($queue);
        
        // process messages
        $this->io->text('Processing messages');
        $this->rabbit->readMessageAsync($handler);
        
        // close connection
        $this->io->text('Closing the connection');
        $this->rabbit->close();

        /** @var \Exception $ex */
        if ($ex = $this->rabbit->exception) {
            if (get_class($ex) === AMQPTimeoutException::class) {
                $this->io->text(
                    'Connection closed automatically as no new messages arrived for: '. RabbitService::DEFAULT_TIMEOUT .' seconds.'
                );
            } else {
                $this->io->text("<error>--- EXCEPTION ---</error>");
                throw $ex;
            }
        }

        $duration = round(microtime(true) - $start, 2);
        $this->io->text("Finished in {$duration} ms");
    }
}
