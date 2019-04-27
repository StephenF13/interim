<?php

namespace App\Controller;

use App\Entity\Assignment;
use App\Form\AssignmentType;
use App\Repository\AssignmentRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/assignment")
 */
class AssignmentController extends AbstractController
{
    /**
     * @Route("/", name="assignment_index", methods={"GET"})
     */
    public function index(AssignmentRepository $assignmentRepository): Response
    {
        return $this->render('assignment/index.html.twig', [
            'assignments' => $assignmentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="assignment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $assignment = new Assignment();
        $form = $this->createForm(AssignmentType::class, $assignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            $lastAssignment = $entityManager->getRepository(Assignment::class)->findOneBy(
                ['interim' => $assignment->getInterim()],
                ['id' => 'DESC']
            );

            $lastAssignment->setStatus('Supprimé');

            // do not allow to change status in form ?
            $assignment->setStatus('Actif');
            $entityManager->persist($assignment);
            $entityManager->flush();

            return $this->redirectToRoute('assignment_index');
        }

        return $this->render('assignment/new.html.twig', [
            'assignment' => $assignment,
            'form'       => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="assignment_show", methods={"GET"})
     */
    public function show(Assignment $assignment): Response
    {
        return $this->render('assignment/show.html.twig', [
            'assignment' => $assignment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="assignment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Assignment $assignment): Response
    {
        $form = $this->createForm(AssignmentType::class, $assignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('assignment_index', [
                'id' => $assignment->getId(),
            ]);
        }

        return $this->render('assignment/edit.html.twig', [
            'assignment' => $assignment,
            'form'       => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="assignment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Assignment $assignment): Response
    {
        if ($this->isCsrfTokenValid('delete' . $assignment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($assignment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('assignment_index');
    }
}
