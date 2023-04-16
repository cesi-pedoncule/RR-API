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
    name: 'app:user:reset-password',
    description: 'Reset the password of a user',
)]
class ResetPasswordUserCommand extends Command
{
    public function __construct(private UserManager $userManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Email of the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('email')) {
            $email = $input->getOption('email');
        } else {
            // Ask user information before reset the password
            $email = $io->ask('Email');
        }
    
        $user = $this->userManager->getUserByEmail($email);

        // Check if the email is valid
        if ($user === null) {
            $io->error('The email is invalid.');
            return Command::FAILURE;
        }

        // Reset the password
        $password = $this->userManager->resetPassword($user);
        $io->success("The password has been reset (new password: $password).");
        return Command::SUCCESS;
    }
}