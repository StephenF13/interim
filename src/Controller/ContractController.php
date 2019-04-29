<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Interim;
use App\Form\ContractType;
use App\Repository\ContractRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContractController extends AbstractController
{
    /**
     * @Route("/contract", name="contract")
     */
    public function index(ContractRepository $contractRepository): Response
    {
        return $this->render('contract/index.html.twig', [
            'contracts' => $contractRepository->findAll(),
        ]);
    }

    /**
     * @Route("/contract/new", name="contract_new")
     */
    public function new(Request $request): Response
    {
        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contract);
            $entityManager->flush();

            return $this->redirectToRoute('contract');
        }

        return $this->render('contract/new.html.twig', [
            'contract' => $contract,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @Route("/contract/{id}", name="contract_show", methods={"GET"})
     */
    public function show(Contract $contract): Response
    {
        return $this->render('contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }

    /**
     * @Route("/contract/{id}/edit", name="contract_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contract $contract): Response
    {
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contract', [
                'id' => $contract->getId(),
            ]);
        }

        return $this->render('contract/edit.html.twig', [
            'contract' => $contract,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @Route("/contract/{id}", name="contract_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contract $contract): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contract->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contract);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contract');
    }

    /**
     * @Route("/search", name="search", methods={"POST"})
     */
    public function searchAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $value = $request->get('value');
        $interims = $em->getRepository(Interim::class)->findByNameOrFirstname($value);
        if (!$value) {
            $result['error'] = "Aucun intérimaire trouvé";
        } else {
            foreach ($interims as $interim) {
                $result['interim'] = $interim->getName();
            }
        }


//        TODO finish this part (ajax search interim in contract form)
        // https://symfony.com/doc/2.3/cookbook/form/data_transformers.html#harder-example-transforming-an-issue-number-into-an-issue-entity
        // need to transform string into entity Interim
        // try to put Interim's Id in <li> and use it with data transformer to send Interim entity and create a contract

        return new JsonResponse($result);
    }

}
