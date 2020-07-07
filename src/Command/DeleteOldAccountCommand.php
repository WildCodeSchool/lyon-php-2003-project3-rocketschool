<?php

namespace App\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\UserRepository;

class DeleteOldAccountCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:delete-old-accounts';
    private $userRepository;

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Deletes old accounts.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows to delete all the user accounts older than 100 days.')
        ;
    }

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->userRepository->deleteOldAccounts();

        $output->writeln('Users successfully deleted!');

        // return Command::SUCCESS;
        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        // The Command::SUCCESS and Command::FAILURE constants were introduced in Symfony 5.1
        return 0;
    }
}
