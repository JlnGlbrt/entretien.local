<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Article
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 *
 * @package App\Entity
 */
class Article extends Post
{

    /**
     * Titre de l'article
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $title;

    /**
     * Image de l'article
     *
     * @ORM\Column(type="string")
     * @Assert\Image(allowLandscape = true, allowPortrait = false)
     * @Assert\NotBlank
     * @Assert\File(mimeTypes={ "image/*" })
     */
    private $image;

    /**
     * Commentaires de l'article
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article", cascade={"remove"})
     */
    private $comments;

    /**
     * Nombre de commentaires de l'article
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbComments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function getNbComments(): ?int
    {
        return $this->nbComments;
    }

    public function setNbComments(?int $nbComments): self
    {
        $this->nbComments = $nbComments;

        return $this;
    }

    /**
     * Fonction qui retourne une chaine unique à partir du pseudo
     * de l'auteur et de la date de publication
     * Cette chaîne servira de nom à l'image de l'article
     *
     * @return null|string
     */
    public function generateUniqueFileName(): ?string
    {
        return $this->getAuthor()->getUsername()."_".$this->getPostedAt()->format('dmYHis');
    }
}
