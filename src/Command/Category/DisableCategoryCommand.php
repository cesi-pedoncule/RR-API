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
    name: 'app:disable-category',
    description: 'Disable a category',
)]
class DisableCategoryCommand extends Command
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

        // Ask the category name
        $categoryName = $io->ask('What is the category name?');

        // Check if the category exists
        $category = $this->categoryManager->findCategoryByName($categoryName);

        if ($category === null) {
            $io->error('The category does not exist');

            return Command::FAILURE;
        }

        // Disable the category
        $this->categoryManager->disableCategory($category);
        $io->success('The category has been disabled.');
        return Command::SUCCESS;
    }
}
