<?php

namespace App\Controller;

use App\Entity\Faq;
use App\Form\FaqType;
use App\Repository\FaqRepository;
use App\Services\FaqMoveItem;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/faq")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("/", name="faq_index", methods={"GET"})
     */
    public function index(FaqRepository $faqRepository): Response
    {
        return $this->render('faq/index.html.twig', [
            'faqs' => $faqRepository->findBy([], ['position'=> 'ASC']),
            'page_name' => 'FAQ - index'
        ]);
    }

    /**
     * @Route("/new", name="faq_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, FaqRepository $faqRepository): Response
    {
        $faq = new Faq();
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        $lastPosition = $faqRepository
            ->findOneBy([], ['position' => 'DESC'])
            ->getPosition();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $faq->setPosition($lastPosition + 1);
            $entityManager->persist($faq);
            $entityManager->flush();

            return $this->redirectToRoute('faq_index');
        }

        return $this->render('faq/new.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
            'page_name' => 'FAQ - Nouvelle question'
        ]);
    }

    /**
     * @Route("/{id}", name="faq_show", methods={"GET"})
     * @param Faq $faq
     * @return Response
     */
    public function show(Faq $faq): Response
    {
        return $this->render('faq/show.html.twig', [
            'faq' => $faq,
            'page_name' => 'FAQ - Aperçu',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="faq_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Faq $faq
     * @return Response
     */
    public function edit(Request $request, Faq $faq): Response
    {
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('faq_index');
        }

        return $this->render('faq/edit.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
            'page_name' => 'FAQ - Edition'
        ]);
    }

    /**
     * @Route("/{id}", name="faq_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Faq $faq): Response
    {
        if ($this->isCsrfTokenValid('delete'.$faq->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($faq);
            $entityManager->flush();
        }

        return $this->redirectToRoute('faq_index');
    }

    /**
     * @Route("/move/{id}/{position}", name="faq_move",methods={"GET", "POST"})
     * @return Response
     */
    public function move(FaqMoveItem $faqMoveItem, Faq $faq, string $position): Response
    {
        $faqMoveItem->move($faq, $position);

        return $this->redirectToRoute('faq_index');
    }
}
