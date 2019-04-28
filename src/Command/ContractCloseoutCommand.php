<?php

namespace App\Command;

use App\Entity\Contract;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ContractCloseoutCommand extends Command
{
    protected static $defaultName = 'app:contract-closeout';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Closing-out contracts exceeding dateEnd');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contracts = $this->entityManager->getRepository(Contract::class)->findCompletedContracts();

        foreach ($contracts as $contract) {
            $contract->setStatus('Terminé');
            $this->entityManager->persist($contract);
        }

        $this->entityManager->flush();

        $output->writeln('Contracts closed-out !');
    }
}
