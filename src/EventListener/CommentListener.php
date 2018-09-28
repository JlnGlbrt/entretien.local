<?php

namespace App\EventListener;

use App\Entity\Comment;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * Class CommentListener
 * Paramétrage fait dans config/services.yaml
 * @package App\EventListener
 */
class CommentListener
{
    /**
     * Méthode qui s'execute après l'évènement de création d'un commentaire
     *
     * @param Comment $comment
     * @param LifecycleEventArgs $args
     */
    public function postPersist(Comment $comment, LifecycleEventArgs $args)
    {
        // On récupère l'article associé au commentaire ajouté
        $article = $comment->getArticle();
        // On incrémente le nombre de commentaires de l'article
        $article->setNbComments($article->getNbComments() + 1);
        // On met à jour l'article en BDD
        $entityManager = $args->getObjectManager();
        $entityManager->persist($article);
        $entityManager->flush();
    }

    /**
     * Méthode qui s'execute après l'évènement de suppression d'un commentaire
     *
     * @param Comment $comment
     * @param LifecycleEventArgs $args
     */
    public function postRemove(Comment $comment, LifecycleEventArgs $args)
    {
        // On récupère l'article associé au commentaire supprimé
        $article = $comment->getArticle();
        // On décrémente me nombre de commentaires de l'article
        $article->setNbComments($article->getNbComments() - 1);
        // On met à jour l'article en BDD
        $entityManager = $args->getObjectManager();
        $entityManager->persist($article);
        $entityManager->flush();
    }
}