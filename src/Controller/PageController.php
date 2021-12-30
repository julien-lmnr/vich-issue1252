<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'page', methods: ['GET', 'POST'])]
    public function index(Request $request, PageRepository $pageRepository): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($page);
            $this->entityManager->flush();

            return $this->redirectToRoute('page');
        }

        return $this->renderForm('page/index.html.twig', [
            'form' => $form,
            'all_pages' => $pageRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'page_edit', methods: ['GET', 'POST'])]
    public function edit(Page $page, Request $request): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($page);
            $this->entityManager->flush();

            return $this->redirectToRoute('page');
        }

        return $this->renderForm('page/edit.html.twig', [
            'form' => $form,
            'page' => $page,
        ]);
    }

    #[Route('/{id}/delete', name: 'page_delete', methods: ['POST'])]
    public function delete(Page $page, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $page->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($page);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('page');
    }
}
