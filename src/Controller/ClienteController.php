<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
#[Route('/cliente')]
class ClienteController extends AbstractController
{
    #[Route('/', name: 'app_cliente_index', methods: ['GET'])]
    public function index(ClienteRepository $clienteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $clienteRepository->getAllClients();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6 /*límite de registros por página*/
        );
        return $this->render('cliente/index.html.twig', [
            'clientes' => $pagination
        ]);
    }

    #[Route('/new', name: 'app_cliente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClienteRepository $clienteRepository): Response
    {
        $cliente = new Cliente();
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clienteRepository->add($cliente, true);

            return $this->redirectToRoute('app_cliente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cliente/new.html.twig', [
            'cliente' => $cliente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cliente_show', methods: ['GET'])]
    public function show(Cliente $cliente): Response
    {
        return $this->render('cliente/show.html.twig', [
            'cliente' => $cliente,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cliente_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cliente $cliente, ClienteRepository $clienteRepository): Response
    {
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clienteRepository->add($cliente, true);

            return $this->redirectToRoute('app_cliente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cliente/edit.html.twig', [
            'cliente' => $cliente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cliente_delete', methods: ['POST'])]
    public function delete(Request $request, Cliente $cliente, ClienteRepository $clienteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cliente->getId(), $request->request->get('_token'))) {
            $clienteRepository->remove($cliente, true);
        }

        return $this->redirectToRoute('app_cliente_index', [], Response::HTTP_SEE_OTHER);
    }
}
