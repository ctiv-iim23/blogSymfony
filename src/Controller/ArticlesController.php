<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function index(EntityManagerInterface $entityManager, Request $request)
    {
        $newArticle = new Articles();

    $form = $this->createForm(ArticlesType::class, $newArticle);

    $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()){
            $newArticle = $form->getData();
            $newArticle->setCreatedAt(new \DateTime());

            $entityManager->persist($newArticle);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        $articles = $this->getDoctrine()
                    ->getRepository(Articles::class)
                    ->findAll();
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'articleForm' => $form->createView()
        ]);
    }
}
