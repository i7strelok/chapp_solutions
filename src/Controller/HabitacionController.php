<?php

namespace App\Controller;

use App\Entity\Habitacion;
use App\Form\HabitacionType;
use App\Repository\HabitacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/habitacion')]
class HabitacionController extends AbstractController
{
    #[Route('/', name: 'app_habitacion_index', methods: ['GET'])]
    public function index(HabitacionRepository $habitacionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $habitacionRepository->getAllRooms();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 /*límite de registros por página*/
        );
        return $this->render('habitacion/index.html.twig', [
            'habitaciones' => $pagination
        ]);
    }

    #[Route('/new', name: 'app_habitacion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HabitacionRepository $habitacionRepository): Response
    {
        $habitacion = new Habitacion();
        $form = $this->createForm(HabitacionType::class, $habitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $habitacionRepository->add($habitacion, true);

            return $this->redirectToRoute('app_habitacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('habitacion/new.html.twig', [
            'habitacion' => $habitacion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habitacion_show', methods: ['GET'])]
    public function show(Habitacion $habitacion): Response
    {
        return $this->render('habitacion/show.html.twig', [
            'habitacion' => $habitacion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_habitacion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Habitacion $habitacion, HabitacionRepository $habitacionRepository): Response
    {
        $form = $this->createForm(HabitacionType::class, $habitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $habitacionRepository->add($habitacion, true);

            return $this->redirectToRoute('app_habitacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('habitacion/edit.html.twig', [
            'habitacion' => $habitacion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habitacion_delete', methods: ['POST'])]
    public function delete(Request $request, Habitacion $habitacion, HabitacionRepository $habitacionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$habitacion->getId(), $request->request->get('_token'))) {
            $habitacionRepository->remove($habitacion, true);
        }

        return $this->redirectToRoute('app_habitacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
