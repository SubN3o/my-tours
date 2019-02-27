<?php

namespace MyToursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeArticle
 *
 * @ORM\Table(name="type_article")
 * @ORM\Entity(repositoryClass="MyToursBundle\Repository\TypeArticleRepository")
 */
class TypeArticle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="typeArticle")
     */
    private $articles;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return TypeArticle
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param mixed $articles
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add article
     *
     * @param \MyToursBundle\Entity\Article $article
     *
     * @return TypeArticle
     */
    public function addArticle(\MyToursBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \MyToursBundle\Entity\Article $article
     */
    public function removeArticle(\MyToursBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }
}
