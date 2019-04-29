<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Repository\ContractRepository;
use App\Form\StatisticsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
    /**
     * @Route("/statistics", name="statistics")
     */
    public function index(Request $request)
    {

        $form = $this->createForm(StatisticsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateStart = $form->get('dateStart')->getData();
            $dateEnd = $form->get('dateEnd')->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $contracts = $entityManager->getRepository(Contract::class)->findBetween($dateStart, $dateEnd);

            $fileName = "export_contracts_" . date("d_m_Y") . ".csv";
            $response = new StreamedResponse();


            $response->setCallback(function () use ($contracts) {
                $handle = fopen('php://output', 'w+');

                // Column in CSV
                fputcsv($handle, [
                    'Interimaire',
                    'Date de début',
                    'Date de fin',
                    'Statut',
                ], ';');

                // Fields
                foreach ($contracts as $contract) {

                    fputcsv($handle, [
                        $contract->getInterim(),
                        $contract->getDateStart()->format('d-m-Y'),
                        $contract->getDateEnd()->format('d-m-Y'),
                        $contract->getStatus(),
                    ], ';');
                }
                fclose($handle);
            });


            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8', 'application/force-download');
            $response->headers->set('Content-Disposition', 'attachment; filename=' . $fileName);


//            dump($response);

            return $response;
        }

        return $this->render('statistics/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
