<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function index()
    {
        $articleRepo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $articleRepo->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article/{id}", name="detailArticle")
     */
    public function detail($id)
    {
        $articleRepo = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepo->findOneBy(['id' => $id]);

        return $this->render('article/detail.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/add/article", name="addArticle")
     */
    public function addArticle(Request $request, SluggerInterface $slugger)
    {
        $directory = 'pictures/';
        $form = $this->createForm(ArticleType::class, new Article());
        $form->handleRequest($request);
        // si le formulaire est valide et envoyé
        if($form->isSubmitted() && $form->isValid()) {
            // je récupère les données du form
            $newArticle = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            /** @var UploadedFile $picture */
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();

                try {
                    $picture->move(
                        $directory,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $newArticle->setpicture($newFilename);
            } // j'insère le nouvel employé en BDD
            $entityManager->persist($newArticle);
            $entityManager->flush();
        
        } else {
            return $this->render('article/addArticle.html.twig', [
                'form' => $form->createView(),
                'errors' => $form->getErrors()
            ]);
        }

        return $this->redirect('/articles');
    }

}
