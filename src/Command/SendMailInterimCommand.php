<?php

namespace App\Command;

use App\Entity\Contract;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SendMailInterimCommand extends Command
{
    protected static $defaultName = 'app:send-mail-interim';
    private $entityManager;
    private $mailer;
    private $engine;

    // container aware command deprecated, need to inject templating engine
    public function __construct(EntityManagerInterface $entityManager, \Swift_Mailer $mailer, EngineInterface $engine)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->engine = $engine;

        parent::__construct();

    }

    protected function configure()
    {
        $this
            ->setDescription('Send emails to interims one day before the beginning of their contract');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $contracts = $this->entityManager->getRepository(Contract::class)->findStartTomorrow();

        // TODO configure swift mailer in .env
        foreach ($contracts as $contract) {
            $interimMail = $contract->getInterim()->getMail();
            $message = (new Swift_Message('Your contract start tomorrow'))
                ->setFrom('send@example.com')
                ->setTo($interimMail)
                ->setBody(
                    $this->engine->render(

                        'emails/contract.html.twig',
                        ['name' => $contract->getInterim()]
                    ),
                    'text/html'
                );

            $this->mailer->send($message);
        }


        $output->writeln('Emails sent !');

    }
}
