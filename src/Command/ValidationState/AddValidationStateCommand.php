<?php

namespace App\Command\ValidationState;

use App\Entity\ValidationState;
use App\Service\ResourceManager;
use App\Service\UserManager;
use App\Service\ValidationStateManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-validation-state',
    description: 'Add a validation state to a resource',
)]
class AddValidationStateCommand extends Command
{
    public function __construct(private ValidationStateManager $validationStateManager, private ResourceManager $resourceManager, private UserManager $userManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        // Ask the resource name
        $resourceName = $io->ask('What is the resource name?');

        // Check if the resource exists
        $resource = $this->resourceManager->findResourceByTitle($resourceName);
        if ($resource === null) {
            $io->error('The resource does not exist');

            return Command::FAILURE;
        }

        // Display the available validation states
        $io->writeln('Available validation states:');
        $io->writeln(ValidationState::VALIDATION_STATE_PENDING . ': Not validated');
        $io->writeln(ValidationState::VALIDATION_STATE_VALIDATED . ': Validated');
        $io->writeln(ValidationState::VALIDATION_STATE_REJECTED . ': Rejected');

        // Ask the validation state
        $validationState = $io->ask('What is the validation state?');

        // Check if the validation state is valid
        if (!in_array($validationState, ValidationState::VALIDATION_STATES)) {
            $io->error('The validation state is not valid');

            return Command::FAILURE;
        }

        // Ask the user email
        $userEmail = $io->ask('What is your email?');

        // Check if the user exists
        $user = $this->userManager->findUserByEmail($userEmail);
        if ($user === null) {
            $io->error('The user does not exist');

            return Command::FAILURE;
        }

        // Create the validation state
        $this->validationStateManager->addValidationState($validationState, $resource, $user);
        $io->success('The ValidationState has been created.');

        return Command::SUCCESS;
    }
}
