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
        //Solución no elegante por falta de tiempo.
        $fecha_inicio = ''; $fecha_fin = ''; $huespedes = ''; $etiquetas = ''; $errors = [];
        if($request->query->has('fecha_inicio') != null){
            if($this->checkdate($request->query->get('fecha_inicio'))){
                $fecha_inicio = $request->query->get('fecha_inicio');
            }else{
                $errors[]='Fecha de inicio de reserva debe ser una fecha con formato d/m/Y.';
            }
        }else{
            $fecha_inicio = strval(date('d/m/Y'));
        }
        if($request->query->has('fecha_fin') != null){
            if ($this->checkdate($request->query->get('fecha_fin'))) {
                $fecha_fin = $request->query->get('fecha_fin');
            }else{
                $errors[]='Fecha de fin de reserva debe ser una fecha con formato d/m/Y.';
            }
        }else{
            $fecha_fin = strval(date("d/m/Y", strtotime(date('d-m-Y')."+ 7 days")));
        } 
        if($request->query->has('huespedes') != null){
            if(is_numeric($request->query->get('huespedes')) && $request->query->get('huespedes') > 0){  
                $huespedes = $request->query->get('huespedes');
            }else{
                $errors[]='Huéspedes debe ser un valor numérico.';
            }
        }else{
            $huespedes = 2;
        } 
        if($request->query->has('etiquetas') != null){ 
            $etiquetas = $request->query->get('etiquetas');
        }else{
            $etiquetas = '';
        }
        if (count($errors) == 0)
        {
            $iDate = \DateTime::createFromFormat('d/m/Y', $fecha_inicio);
            $fDate = \DateTime::createFromFormat('d/m/Y', $fecha_fin);
            if (strtotime($fDate->format('Y-m-d')) > strtotime($iDate->format('Y-m-d')))
            {
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
                'huespedes' => $huespedes,
                'errors' => $errors,
                ]);
            } else {
                $errors[]= 'Fecha de fin no puede ser inferior o igual a la fecha de inicio.';
            }
        }
        return $this->render('reserva/filter.html.twig', [
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'etiquetas' => $etiquetas,
            'huespedes' => $huespedes,
            'errors' => $errors,
        ]);
    }

    #[Route('/reserva/new', name: 'app_reserva_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservaRepository $reservaRepository): Response
    {
        //Solución no elegante por falta de tiempo.
        $reserva = new Reserva();
        $errors = [];
        if($request->query->has('fecha_inicio') != null && $this->checkdate($request->query->get('fecha_inicio')))
        {   
            if($request->query->has('fecha_fin') != null && $this->checkdate($request->query->get('fecha_fin'))){
                $iDate = \DateTime::createFromFormat('d/m/Y', $request->query->get('fecha_inicio'));
                $iiDate = \DateTime::createFromFormat('Y-m-d', $iDate->format('Y-m-d'));
                $fDate = \DateTime::createFromFormat('d/m/Y', $request->query->get('fecha_fin'));
                $ffDate = \DateTime::createFromFormat('Y-m-d', $fDate->format('Y-m-d'));
                if (strtotime($fDate->format('Y-m-d')) > strtotime($iDate->format('Y-m-d'))) {
                    $reserva->setFechaFin($ffDate);
                    $reserva->setFechaInicio($iiDate);
                }else{
                    $errors[]= 'Fecha de fin no puede ser inferior o igual a la fecha de inicio.';                  
                }
            }else{
                $errors[]= 'Fecha de fin no válida.';
            }
        }else{
            $errors[]= 'Fecha de inicio no válida.';
        }
        if($request->query->has('huespedes') != null && is_numeric($request->query->get('huespedes')) &&
        $request->query->get('huespedes') > 0){
            $reserva->setNumeroHuespedes($request->query->get('huespedes')); 
        }else{
            $errors[]= 'Número de huéspedes no válido.';
        }
        if($request->query->has('habitacion_id') != null && is_numeric($request->query->get('habitacion_id')) &&
            $request->query->get('habitacion_id') > 0){
            $habitacion = $this->getDoctrine()
            ->getRepository(Habitacion::class)
            ->find($request->query->get('habitacion_id'));
            if (!$habitacion) {
                $error[]= 'Habitación no encontrada';
            }else $reserva->setHabitacion($habitacion);
        }else{
            $errors[]= 'Habitación no válida.';
        }
        $form = $this->createForm(ReservaType::class, $reserva);
        $form->handleRequest($request);
        /*
            isValid() ejecuta las validaciones que definimos en el Entity.
            isSubmitted() comprueba si se ha enviado.
        */
        if ($form->isSubmitted() && $form->isValid()) {
            $availableRooms = $this->getDoctrine()
            ->getRepository(Habitacion::class)->getAvailableRooms($reserva->getFechaInicio(), $reserva->getFechaFin(), $reserva->getNumeroHuespedes(), "")
            ->getResult();
            $isAvailable = false;
            foreach($availableRooms as $room){
                if($room->getId() == $reserva->getHabitacion()->getId()) $isAvailable = true;
            }
            if ($isAvailable) {
                $reserva->setLocalizador(uniqid());
                $reservaRepository->add($reserva, true);
                return $this->redirectToRoute('app_reserva_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $errors[]='Habitación no disponible.';
            }
            
        }

        return $this->renderForm('reserva/new.html.twig', [
            'reserva' => $reserva,
            'form' => $form,
            'errors' => $errors,
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

    private function checkdate($date) {
        $tempDate = explode('/', $date);
        // checkdate(month, day, year)
        return checkdate($tempDate[1], $tempDate[0], $tempDate[2]);
      }
}
