<?php

namespace App\Command\Category;

use App\Service\CategoryManager;
use App\Service\UserManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-category',
    description: 'Create a new category',
)]
class CreateCategoryCommand extends Command
{
    public function __construct(private CategoryManager $categoryManager, private UserManager $userManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Ask the current user
        $userEmail = $io->ask('What is your email?');

        // Check if the current user exists and have Admin Role
        $currentUser = $this->userManager->findUserByEmail($userEmail);

        if ($currentUser === null) {
            $io->error('The user does not exist');

            return Command::FAILURE;
        }

        // Ask the category name
        $categoryName = $io->ask('What is the category name?');

        // Ask if the category is visible
        $categoryIsVisible = $io->confirm('Is the category visible?');

        // Check if the category name is available
        if (!$this->categoryManager->checkIfNewCategoryNameIsAvailable($categoryName)) {
            $io->error('The category name is not available');

            return Command::FAILURE;
        }

        // Create the category
        $category = $this->categoryManager->createNewCategory($currentUser, $categoryName, $categoryIsVisible);

        $io->success('The new category has been created !');

        return Command::SUCCESS;
    }
}
