<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Articles", mappedBy="categorieId")
     */
    private $listArticles;

    public function __construct()
    {
        $this->listArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getListArticles(): Collection
    {
        return $this->listArticles;
    }

    public function addListArticle(Articles $listArticle): self
    {
        if (!$this->listArticles->contains($listArticle)) {
            $this->listArticles[] = $listArticle;
            $listArticle->setCategorieId($this);
        }

        return $this;
    }

    public function removeListArticle(Articles $listArticle): self
    {
        if ($this->listArticles->contains($listArticle)) {
            $this->listArticles->removeElement($listArticle);
            // set the owning side to null (unless already changed)
            if ($listArticle->getCategorieId() === $this) {
                $listArticle->setCategorieId(null);
            }
        }

        return $this;
    }
}
