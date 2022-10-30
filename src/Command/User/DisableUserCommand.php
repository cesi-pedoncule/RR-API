<?php

namespace App\Command\User;

use App\Service\UserManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:disable-user',
    description: 'Disable a user',
)]
class DisableUserCommand extends Command
{
    public function __construct(private UserManager $userManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        // Ask the email of the user to disable
        $email = $io->ask('Email of user account to disable');

        // Find the user
        $user = $this->userManager->findUserByEmail($email);

        // Disable the user
        if ($this->userManager->disableUser($user, $user)) {
            $io->success('The user account has been disabled.');
            return Command::SUCCESS;
        } else {
            $io->error('The user account has not been disabled.');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
