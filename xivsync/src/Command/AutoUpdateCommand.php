<?php

namespace App\Command;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

use App\Services\Game\CharacterService,
    App\Services\Game\FreeCompanyService,
    App\Services\Game\LinkshellService;
use XIVCommon\Logger\Logger;

class AutoUpdateCommand extends ContainerAwareCommand
{
    /** @var SymfonyStyle */
    private $io;
    /** @var CharacterService */
    private $characters;
    /** @var FreeCompanyService */
    private $freecompany;
    /** @var LinkshellService */
    private $linkshells;

    public function __construct(
        CharacterService $characterService,
        FreeCompanyService $freeCompanyService,
        LinkshellService $linkshellService,
        $name = null
    ) {
        parent::__construct($name);

        $this->characters = $characterService;
        $this->freecompany = $freeCompanyService;
        $this->linkshells = $linkshellService;
    }

    /**
     * Configure search engine
     */
    protected function configure()
    {
        $this
            ->setName('app:auto')
            ->setDescription('XIVSync Auto Operations')
            ->setHelp('Provide an action, server and an offset.')
            ->addArgument('action', InputArgument::REQUIRED, 'The Auto-Update action to run')
            ->addArgument('server', InputArgument::REQUIRED, 'The server number.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->characters->setSymfonyStyle($this->io);
        $this->freecompany->setSymfonyStyle($this->io);
        $this->linkshells->setSymfonyStyle($this->io);

        [$cmd, $action, $server] = array_values($input->getArguments());
        
        $this->io->text('XIVSYNC');
        $this->io->newLine();
        $this->io->table(
            [ 'Action', 'Server' ],
            [
                [ $action, $server ]
            ]
        );

        Logger::info(__CLASS__, "Executed with params: Action = {$action}, Server = {$server}");

        // switch based on action
        switch($action) {
            default:
                $output->writeln("<error>Unrecognised action: {$action}</error>");
                $output->writeln([
                    '- characters_add',
                    '- characters_update',
                    '- characters_delete',
                    '- characters_achievements',
                    '- characters_friends',
                    '- freecompany_add',
                    '- freecompany_update',
                    '- linkshells_add',
                    '- linkshells_update',
                ]);
                break;

            case 'characters_add':
                $this->characters->add();
                break;

            case 'characters_update':
                $this->characters->update($server);
                break;

            case 'characters_delete':
                $this->characters->delete();
                break;

            case 'characters_achievements':
                $this->characters->achievements($server);
                break;

            case 'characters_friends':
                $this->characters->friends($server);
                break;

            case 'freecompany_add':
                $this->freecompany->add();
                break;

            case 'freecompany_update':
                $this->freecompany->update($server);
                break;

            case 'linkshells_add':
                $this->linkshells->add();
                break;

            case 'linkshells_update':
                $this->linkshells->update($server);
                break;
        }

        $this->io->text('✓ Complete');
        Logger::info(__CLASS__, "Completed with params: Action = {$action}, Server = {$server}");
    }
}
