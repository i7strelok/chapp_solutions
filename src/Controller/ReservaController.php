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
use \Illuminate\Database\Eloquent\ModelNotFoundException;

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

    #[Route('/reserva/filter/', name: 'app_reserva_filter', methods: ['GET'])]
    public function filter(Request $request, ReservaRepository $reservaRepository, PaginatorInterface $paginator): Response
    {
        $fecha_inicio = ''; $fecha_fin = ''; $huespedes = ''; $etiquetas = '';

        if($request->query->has('fecha_inicio') != null && $this->checkdate($request->query->get('fecha_inicio'))){
            $fecha_inicio = $request->query->get('fecha_inicio');
        }else{
            $fecha_inicio = strval(date('d/m/Y'));
        }
        if($request->query->has('fecha_fin') != null && $this->checkdate($request->query->get('fecha_fin'))){
            $fecha_fin = $request->query->get('fecha_fin'); 
        }else{
            $fecha_fin = strval(date("d/m/Y", strtotime(date('d-m-Y')."+ 7 days")));
        } 
        if($request->query->has('huespedes') != null && is_numeric($request->query->get('huespedes'))){  
            $huespedes = $request->query->get('huespedes');
        }else{
            $huespedes = 2;
        } 
        if($request->query->has('etiquetas') != null){ 
            $etiquetas = $request->query->get('etiquetas');
        }else{
            $etiquetas = '';
        }          
        #Creamos la fecha según el formato de entrada
        $iDate = \DateTime::createFromFormat('d/m/Y', $fecha_inicio);
        $fDate = \DateTime::createFromFormat('d/m/Y', $fecha_fin);
        $query = $this->getDoctrine()
        ->getRepository(Habitacion::class)->getAvailableRooms($iDate->format('Y-m-d'), $fDate->format('Y-m-d'), $huespedes, $etiquetas);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6 /*límite de registros por página*/
        );
        return $this->render('reserva/filter.html.twig', [
            'habitaciones' => $pagination,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'etiquetas' => $etiquetas,
            'huespedes' => $huespedes
        ]);
    }

    #[Route('/reserva/new', name: 'app_reserva_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservaRepository $reservaRepository): Response
    {
        $reserva = new Reserva();
        if($request->query->has('fecha_inicio') != null && $this->checkdate($request->query->get('fecha_inicio'))){
            $iDate = \DateTime::createFromFormat('d/m/Y', $request->query->get('fecha_inicio'));
            $iiDate = \DateTime::createFromFormat('Y-m-d', $iDate->format('Y-m-d'));
            $reserva->setFechaInicio($iiDate);     
        }else{
            throw $this->createNotFoundException(
                'Fecha de inicio no válida.'
            );
        }
        if($request->query->has('fecha_fin') != null && $this->checkdate($request->query->get('fecha_fin'))){
            $fDate = \DateTime::createFromFormat('d/m/Y', $request->query->get('fecha_fin'));
            $ffDate = \DateTime::createFromFormat('Y-m-d', $fDate->format('Y-m-d'));
            $reserva->setFechaFin($ffDate);
        }else{
            throw $this->createNotFoundException(
                'Fecha de fin no válida.'
            );
        }
        if($request->query->has('huespedes') != null && is_numeric($request->query->get('huespedes'))){
            $reserva->setNumeroHuespedes($request->query->get('huespedes')); 
        }else{
            throw $this->createNotFoundException(
                'Número de huéspedes no válido.'
            );
        }
        if($request->query->has('habitacion_id') != null && is_numeric($request->query->get('habitacion_id'))){
            $habitacion = $this->getDoctrine()
            ->getRepository(Habitacion::class)
            ->find($request->query->get('habitacion_id'));
            $reserva->setHabitacion($habitacion);
            if (!$habitacion) {
                throw $this->createNotFoundException(
                    'Habitación no encontrada'
                );
            }
        }else{
            throw $this->createNotFoundException(
                'Habitación no válida.'
            );
        }
        $form = $this->createForm(ReservaType::class, $reserva);
        $form->handleRequest($request);
        /*
            isValid() ejecuta las validaciones que definimos en el Entity.
            isSubmitted() comprueba si se ha enviado.
        */
        if ($form->isSubmitted() && $form->isValid()) {
            $reserva->setLocalizador('testasdasd');
            $reservaRepository->add($reserva, true);
            return $this->redirectToRoute('app_reserva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reserva/new.html.twig', [
            'reserva' => $reserva,
            'form' => $form,
        ]);
    }

    #[Route('/reserva/{id}', name: 'app_reserva_show', methods: ['GET'])]
    public function show(Reserva $reserva): Response
    {
        return $this->render('reserva/show.html.twig', [
            'reserva' => $reserva,
        ]);
    }

    #[Route('/reserva/{id}/edit', name: 'app_reserva_edit', methods: ['GET', 'POST'])]
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

    #[Route('/reserva/{id}', name: 'app_reserva_delete', methods: ['POST'])]
    public function delete(Request $request, Reserva $reserva, ReservaRepository $reservaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reserva->getId(), $request->request->get('_token'))) {
            $reservaRepository->remove($reserva, true);
        }

        return $this->redirectToRoute('app_reserva_index', [], Response::HTTP_SEE_OTHER);
    }

    private function generateReservationNumber(){
        return 'CH'.date("mdHis").'-'.rand(1, 1000);
    }

    private function checkdate($date) {
        $tempDate = explode('/', $date);
        // checkdate(month, day, year)
        return checkdate($tempDate[1], $tempDate[0], $tempDate[2]);
      }
}
