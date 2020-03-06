<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
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
    
        $categorieRepository = $this->getDoctrine()
                            ->getRepository(Categories::class);

        if ($form->isSubmitted() && $form->isValid()){

            $categorie = $categorieRepository->find($request->request->get('categorieId'));
            
            $newArticle = $form->getData();
            $newArticle->setCategorieId($categorie);
            $newArticle->setCreatedAt(new \DateTime());

            $entityManager->persist($newArticle);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        $articles = $this->articlesRepository = $this->getDoctrine()
        ->getRepository(Articles::class)->findAll();
        $categories = $categorieRepository->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'articleForm' => $form->createView(),
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/articles/remove/{id}", name="removeArticle")
     */
    public function remove($id, EntityManagerInterface $entityManager){

        $article = $this->articlesRepository = $this->getDoctrine()
                        ->getRepository(Articles::class)->find($id);
        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirectToRoute('articles');
    }

    /**
     * @Route("/articles/update/{id}", name="updateArticle")
     */
    public function update($id, Request $request, EntityManagerInterface $entityManager){

        $article = $this->getDoctrine()
                        ->getRepository(Articles::class)->find($id);
        $categorieRepository = $this->getDoctrine()
                        ->getRepository(Categories::class);
        $categories = $categorieRepository->findAll();

        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()){

            $categorie = $categorieRepository->find($request->request->get('categorieId'));
            
            $newArticle = $form->getData();
            $newArticle->setCategorieId($categorie);

            $entityManager->persist($newArticle);
            $entityManager->flush();

            return $this->redirectToRoute('articles');
        }
        
        return $this->render('articles/update.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }
}
