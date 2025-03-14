<?php

namespace App\Command;

use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'test:media',
    description: 'Add a short description for your command',
)]
class TestMediaCommand extends Command
{
    public function __construct(private readonly EasyMediaManager $mediaManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // $media = $this->mediaManager->createMedia("TEST", '/test/');

        $media = $this->mediaManager->createMedia('https://images.unsplash.com/photo-1634513389146-87627b4946b9?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=687&q=80');
        dump($media);

        // $folder = $this->mediaManager->folderByPath("/test/coucou/qsdfqsdfsdf");
        // dump($folder);

        return Command::SUCCESS;
    }
}
