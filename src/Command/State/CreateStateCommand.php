<?php

namespace App\Command\State;

use App\Service\StateManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-default-states',
    description: 'Create defaults states',
)]
class CreateStateCommand extends Command
{
    public function __construct(private StateManager $stateManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->stateManager->createDefaultStates();
        $io->success('The default states have been created');
        return Command::SUCCESS;
    }
}
