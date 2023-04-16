<?php

namespace App\Command\User;

use App\Entity\User;
use App\Service\UserManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new user',
)]
class CreateUserCommand extends Command
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
    
        // Ask user's information before creating the user
        $email = $io->ask('Email');
        $password = $io->askHidden('Password');
        $firstname = $io->ask('Firstname');
        $lastname = $io->ask('Lastname');
        $roles = $io->ask('Roles (separated by comma)');
        $roles = (!empty($roles)) ? explode(',', $roles) : ['ROLE_USER'];

        // Create the user
        if ($this->userManager->createUser($email, $password, $lastname, $firstname, $roles)) {
            $io->success('The user account has been created.');
            return Command::SUCCESS;
        } else {
            $io->error('The email is already used or email is invalid.');
            return Command::FAILURE;
        }
    }
}
