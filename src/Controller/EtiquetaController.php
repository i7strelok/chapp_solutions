<?php

namespace App\Controller;

use App\Entity\Etiqueta;
use App\Form\EtiquetaType;
use App\Repository\EtiquetaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/etiqueta')]
class EtiquetaController extends AbstractController
{
    #[Route('/', name: 'app_etiqueta_index', methods: ['GET'])]
    public function index(EtiquetaRepository $etiquetaRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $etiquetaRepository->getAllLabels();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 /*límite de registros por página*/
        );
        return $this->render('etiqueta/index.html.twig', [
            'etiquetas' => $pagination
        ]);
    }

    #[Route('/new', name: 'app_etiqueta_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EtiquetaRepository $etiquetaRepository): Response
    {
        $etiqueta = new Etiqueta();
        $form = $this->createForm(EtiquetaType::class, $etiqueta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etiquetaRepository->add($etiqueta, true);

            return $this->redirectToRoute('app_etiqueta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etiqueta/new.html.twig', [
            'etiqueta' => $etiqueta,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etiqueta_show', methods: ['GET'])]
    public function show(Etiqueta $etiqueta): Response
    {
        return $this->render('etiqueta/show.html.twig', [
            'etiqueta' => $etiqueta,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etiqueta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etiqueta $etiqueta, EtiquetaRepository $etiquetaRepository): Response
    {
        $form = $this->createForm(EtiquetaType::class, $etiqueta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etiquetaRepository->add($etiqueta, true);

            return $this->redirectToRoute('app_etiqueta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etiqueta/edit.html.twig', [
            'etiqueta' => $etiqueta,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etiqueta_delete', methods: ['POST'])]
    public function delete(Request $request, Etiqueta $etiqueta, EtiquetaRepository $etiquetaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etiqueta->getId(), $request->request->get('_token'))) {
            $etiquetaRepository->remove($etiqueta, true);
        }

        return $this->redirectToRoute('app_etiqueta_index', [], Response::HTTP_SEE_OTHER);
    }
}
