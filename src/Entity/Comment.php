<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\EntityListeners({"App\EventListener\CommentListener"})
 *
 * @package App\Entity
 */
class Comment extends Post
{

    /**
     * Article associé au commentaire
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="comments", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
