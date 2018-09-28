<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends AbstractController
{

    /**
     * Affichage des articles
     *
     * @Route("/", name="articles")
     *
     * @param ArticleRepository $articleRepository
     * @param Security $security
     * @return Response
     */
    public function showArticles(ArticleRepository $articleRepository, Security $security): Response
    {
        // On récupère l'utilisateur courant
        $user = $security->getUser();

        // On récupère les 10 articles les plus récents en base
        $latestArticles = $articleRepository->findBy(array(), array('postedAt' => 'DESC'), 10);
        // On retire l'article le plus récent pour en faire l'article principal
        $topArticle = array_shift($latestArticles);

        // On retourne la vue
        return $this->render('article/articles.html.twig', array(
            'user' => $user,
            'topArticle' => $topArticle,
            'latestArticles' => $latestArticles
        ));
    }

    /**
     * Publication d'un article
     *
     * @Route("/publish", name="publish")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @param Request $request
     * @param Security $security
     * @return Response
     */
    public function publish(Request $request, Security $security): Response
    {
        // On récupère le formulaire de création d'un article
        $form = $this->createForm(ArticleType::class, new Article());
        $form->handleRequest($request);

        // On récupère l'utilisateur courant
        $user = $security->getUser();

        // Si le formulaire à été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère l'instance du formulaire
            $article = $form->getData();
            // On renseigne la datetime de création et l'auteur
            $article->setPostedAt(new \DateTime());
            $article->setAuthor($user);

            // On enregistre le fichier de l'article
            $file = $article->getImage();
            $fileName = $article->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('article_image_dir'),
                $fileName
            );
            $article->setImage($fileName);

            // On insère l'article en BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            // On retourne à la page principale
            return $this->redirectToRoute('articles');
        }

        // On retourne la vue
        return $this->render('article/publish.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Affichage d'un article et de ses commentaires
     *
     * @Route("/articles/{id}", name="article")
     *
     * @param Request $request
     * @param Article $article
     * @param Security $security
     * @return Response
     */
    public function showArticle(Request $request, Article $article, Security $security): Response
    {
        // On récupère le formulaire de création d'un commentaire
        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);

        // On récupère l'utilisateur courant
        $user = $security->getUser();

        // Si le formulaire de création d'un commentaire à été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère l'instance du formulaire
            $comment = $form->getData();
            // On renseigne la datetime de création et l'auteur
            $comment->setPostedAt(new \DateTime());
            $comment->setAuthor($user);

            // On ajoute le commentaire à l'article courant
            $article->addComment($comment);
            // On insère le commentaire en BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            // On initialise le formulaire de création de commentaire
            $form = $this->createForm(CommentType::class);
        }

        // On récupère les commentaires de l'article
        $comments = $article->getComments();

        // On retourne la vue
        return $this->render('article/article.html.twig', [
            'user' => $security->getUser(),
            'article' => $article,
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * Suppression d'un article
     *
     * @Route("/article/delete/{id}", name="dl-article")
     *
     * @param Article $article
     * @return Response
     */
    public function deleteArticle(Article $article): Response
    {
        // On supprime tous les commentaires associés à l'article
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($article->getComments() as $comment) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        // On supprime l'article
        $entityManager->remove($article);
        $entityManager->flush();
        $this->addFlash('deleteArticle', 'L\'article a bien étais supprimer');

        // On retourne à la liste des articles
        return $this->redirectToRoute('articles');
    }


    /**
     * Suppression d'un commentaire
     *
     * @Route("/comment/delete/{id}", name="dl-comment")
     *
     * @param Comment $comment
     * @return Response
     */
    public function deleteComment(Comment $comment): Response
    {
        // On recupère l'article associé au commentaire
        $article = $comment->getArticle();

        // On supprime le commentaire
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        // On retourne à la page de l'article associé au commentaire supprimé
        return $this->redirect($this->generateUrl('article', array('id' => $article->getId())));
    }
}
