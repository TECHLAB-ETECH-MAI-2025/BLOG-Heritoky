<?php

namespace App\Entity;

use App\Repository\ArticleLikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleLikeRepository::class)]
class ArticleLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?Article $article = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?String $emailLike = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getEmailLike(): ?String
    {
        return $this->emailLike;
    }

    public function setEmailLike(?String $emailLike): static
    {
        $this->emailLike = $emailLike;

        return $this;
    }
}
