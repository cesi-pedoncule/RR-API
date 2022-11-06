<?php

namespace App\Command\Resource;

use App\Service\CategoryManager;
use App\Service\ResourceManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-resource',
    description: 'Create a new resource',
)]
class CreateResourceCommand extends Command
{
    public function __construct(private ResourceManager $resourceManager, private CategoryManager $categoryManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $title = $io->ask('What is the resource name?');
        $resource = $this->resourceManager->findResourceByTitle($title);
        
        if ($resource !== null) {
            $io->error('The resource already exists');
            return Command::FAILURE;
        }

        $description = $io->ask('What is the resource description?');

        $attachments = [];

        $isPublic = $io->confirm('Is the resource public?');

        $categoryName = $io->ask('What is the resource category?');
        $category = $this->categoryManager->findCategoryByName($categoryName);

        if ($category === null) {
            $io->error('The category does not exist');
            return Command::FAILURE;
        }

        $categories[] = $category;

        $resource = $this->resourceManager->createResource($title, $description, $attachments, $isPublic, $categories);

        if ($resource === null) {
            $io->error('The resource could not be created');
            return Command::FAILURE;
        } else {
            $io->success('The resource has been created');
            return Command::SUCCESS;
        }
    }
}