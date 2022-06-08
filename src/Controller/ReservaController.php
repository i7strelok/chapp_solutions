<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Habitacion;
use App\Form\ReservaType;
use App\Repository\ReservaRepository;
use App\Repository\HabitacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/reserva')]
class ReservaController extends AbstractController
{
    #[Route('/', name: 'app_reserva_index', methods: ['GET'])]
    public function index(ReservaRepository $reservaRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $reservaRepository->getAllBookings();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 /*límite de registros por página*/
        );
        return $this->render('reserva/index.html.twig', [
            'reservas' => $pagination
        ]);
    }

    #[Route('/filter', name: 'app_reserva_filter', methods: ['GET'])]
    public function filter(Request $request, ReservaRepository $reservaRepository, PaginatorInterface $paginator): Response
    {
        $fecha_inicio = ''; $fecha_fin = ''; $huespedes = '';
        if($request->query->has('fecha_inicio') === null){ 
            $fecha_inicio = date('d-m-Y');
        }else{
            $fecha_inicio = $request->request->get('fecha_inicio');
        }
        if($request->query->has('fecha_fin') === null){ 
            $fecha_fin = date("d-m-Y", strtotime(date('d-m-Y')."+ 7 days")); 
        }else{
            $fecha_fin = $request->request->get('fecha_fin');
        }   
        if($request->query->has('huespedes') === null){ 
            $huespedes = 2;
        }else{
            $huespedes = $request->request->get('huespedes');
        }     
        //$habitacionRepository = new HabitacionRepository();
        $query = $this->getDoctrine()
        ->getRepository(Habitacion::class)->getAvailableRooms($fecha_inicio, $fecha_fin, $huespedes);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 /*límite de registros por página*/
        );
        return $this->render('reserva/filter.html.twig', [
            'habitaciones' => $pagination
        ]);
    }

    #[Route('/new', name: 'app_reserva_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservaRepository $reservaRepository): Response
    {
        $reserva = new Reserva();
        $form = $this->createForm(ReservaType::class, $reserva);
        $form->handleRequest($request);
        /*
            isValid() ejecuta las validaciones que definimos en el Entity.
            isSubmitted() comprueba si se ha enviado.
        */
        if ($form->isSubmitted() && $form->isValid()) {
            $reservaRepository->add($reserva, true);

            return $this->redirectToRoute('app_reserva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reserva/new.html.twig', [
            'reserva' => $reserva,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reserva_show', methods: ['GET'])]
    public function show(Reserva $reserva): Response
    {
        return $this->render('reserva/show.html.twig', [
            'reserva' => $reserva,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reserva_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reserva $reserva, ReservaRepository $reservaRepository): Response
    {
        $form = $this->createForm(ReservaType::class, $reserva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservaRepository->add($reserva, true);

            return $this->redirectToRoute('app_reserva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reserva/edit.html.twig', [
            'reserva' => $reserva,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reserva_delete', methods: ['POST'])]
    public function delete(Request $request, Reserva $reserva, ReservaRepository $reservaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reserva->getId(), $request->request->get('_token'))) {
            $reservaRepository->remove($reserva, true);
        }

        return $this->redirectToRoute('app_reserva_index', [], Response::HTTP_SEE_OTHER);
    }
}
