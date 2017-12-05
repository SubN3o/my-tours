<?php

namespace MyOrleansBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\ORM\Mapping\JoinTable;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\ArticleRepository")
 */
class Article
{

    CONST NUM_ARTICLES = 9;

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
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 45,
     *      minMessage = "Le titre saisi est court.",
     *      maxMessage = "Le titre saisi est long."
     * )
     * @ORM\Column(name="titre", type="string", length=45)
     */
    private $titre;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     * @ORM\Column(name="texte", type="text")
     */
    private $texte;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity="Media", cascade={"persist"})
     * @JoinTable(name="article_media")
     * @Assert\Valid()
     */
    private $medias;


    /**
     * @ORM\ManyToOne(targetEntity="Residence",cascade={"persist"})
     * @JoinTable(name="article_media")
     * @Assert\NotNull()
     */
    private $residence;

    /**
     * @ORM\ManyToMany(targetEntity="Tag",cascade={"persist"})
     * @JoinTable(name="article_tag")
     * @Assert\NotNull()
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="TypeArticle", inversedBy="articles", cascade={"persist"})
     * @Assert\NotNull()
     */
    private $typeArticle;

    /**
     * @var string
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Veuillez télécharger un fichier PDF valide"
     * )
     * @ORM\Column(name="fichier", type="string", nullable=true)
     */
    private $fichierAssocie;

    /**
     * @var string
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(name="slug", type="string")
     */
    private $slug;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="La saisie n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Saisissez une valeur supérieure ou égale à 1",
     *)
     * @ORM\Column(name="tri", type="integer", nullable=true)
     */private $tri;

    /**
     * @return int
     */
    public function getTri()
    {
        return $this->tri;
    }

    /**
     * @param int $tri
     * @return Article
     */
    public function setTri($tri)
    {
        $this->tri = $tri;
        return $this;
    }

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
     * Set titre
     *
     * @param string $titre
     *
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set texte
     *
     * @param string $texte
     *
     * @return Article
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * Set residence
     *
     * @param \MyOrleansBundle\Entity\Residence $residence
     *
     * @return Article
     */
    public function setResidence(\MyOrleansBundle\Entity\Residence $residence = null)
    {
        $this->residence = $residence;

        return $this;
    }

    /**
     * Get residence
     *
     * @return \MyOrleansBundle\Entity\Residence
     */
    public function getResidence()
    {
        return $this->residence;
    }

    /**
     * @return mixed
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param mixed $medias
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getTypeArticle()
    {
        return $this->typeArticle;
    }

    /**
     * @param mixed $typeArticle
     */
    public function setTypeArticle($typeArticle)
    {
        $this->typeArticle = $typeArticle;
    }


    /**
     * Add media
     *
     * @param \MyOrleansBundle\Entity\Media $media
     *
     * @return Article
     */
    public function addMedia(\MyOrleansBundle\Entity\Media $media)
    {
        $this->medias[] = $media;
        $media->setArticles($this);
        return $this;

    }

    /**
     * Remove media
     *
     * @param \MyOrleansBundle\Entity\Media $media
     */
    public function removeMedia(\MyOrleansBundle\Entity\Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Add tag
     *
     * @param \MyOrleansBundle\Entity\Tag $tag
     *
     * @return Article
     */
    public function addTag(\MyOrleansBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;
        $tag->setArticles($this);
        return $this;
    }

    /**
     * Remove tag
     *
     * @param \MyOrleansBundle\Entity\Tag $tag
     */
    public function removeTag(\MyOrleansBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @return string
     */
    public function getFichierAssocie()
    {
        return $this->fichierAssocie;
    }

    /**
     * @param string $fichierAssocie
     */
    public function setFichierAssocie(string $fichierAssocie)
    {
        $this->fichierAssocie = $fichierAssocie;
    }



    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
