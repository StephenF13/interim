<?php

namespace App\Command;

use App\Entity\Interim;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CityCommand extends Command
{
    protected static $defaultName = 'app:city';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add city in database when field is empty using zipcode');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $interims = $this->entityManager->getRepository(Interim::class)->findBy(
            ['city' => null]
        );

        foreach ($interims as $interim) {
            $zipcode = $interim->getZipcode();
            $city =  file_get_contents('https://geo.api.gouv.fr/communes?codePostal='.$zipcode);
            $city = json_decode($city);
            if ($city) {
                $interim->setCity($city[0]->nom);
            }
            $this->entityManager->persist($interim);
        }

        $this->entityManager->flush();

        $output->writeln('Cities added to database !');


        // Problem when we have many cities for one zipcode
        // Here i choose the first city

    }
}
