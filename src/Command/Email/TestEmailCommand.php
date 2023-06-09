<?php

namespace App\Command\Email;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:email:test',
    description: 'Send a test email',
)]
class TestEmailCommand extends Command
{
    public function __construct(private MailerInterface $mailer)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
    
        // Ask email address
        $email = $io->ask('Email');

        // Send the email
        $email = (new Email())
            ->from('Resources Relationnelles <contact@aymeric-cucherousset.fr>')
            ->to($email)
            ->subject('Test email')
            ->text('This is a test email')
            ->html('<p>This is a test email</p>');

        $this->mailer->send($email);

        $io->success('The test email has been sent.');
        return Command::SUCCESS;
    }
}
