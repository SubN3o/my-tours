<?php

namespace MyOrleansBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * Media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\MediaRepository")
 * @Vich\Uploadable
 */
class Media
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
     * @Assert\File()
     * @ORM\Column(name="lien", type="text", nullable=true)
     */
    private $lien;

    /**
     * @ORM\ManyToMany(targetEntity="Flat", cascade={"persist"})
     */
    private $flats;

    /**
     * @ORM\ManyToMany(targetEntity="Residence", cascade={"persist"})
     *
     */
    private $residences;

    /**
     * @ORM\OneToOne(targetEntity="Evenement", mappedBy="media")
     */
    private $evenement;


    /**
     * @ORM\OneToOne(targetEntity="Partenaire", mappedBy="media")
     */
    private $partenaire;

    /**
     * @ORM\OneToOne(targetEntity="CategoriePresta", mappedBy="media")
     */
    private $categorie_presta;

    /**
     * @ORM\OneToOne(targetEntity="Service", mappedBy="media")
     */
    private $service;

    /**
     * @ORM\OneToOne(targetEntity="Collaborateur", mappedBy="media")
     */
    private $collaborateur;

    /**
     * @ORM\OneToOne(targetEntity="Pack", mappedBy="media")
     */
    private $pack;

    /**
     * @ORM\ManyToMany(targetEntity="Article",cascade={"persist"})
     */
    private $articles;

    /**
     * @ORM\ManyToOne(targetEntity="TypeMedia", inversedBy="medias", cascade={"persist"})
     */
    private $typeMedia;

    /**
     * @Vich\UploadableField(mapping="media", fileNameProperty="mediaName")
     *
     * @var File
     */
    private $mediaFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $mediaName;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @param \DateTime $updatedAt
     * @return Media
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
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
     * Set lien
     *
     * @param string $lien
     *
     * @return Media
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string
     */
    public function getLien()
    {
        return $this->lien;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->residences = new ArrayCollection();
        $this->flats = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }


    /**
     * Set evenement
     *
     * @param \MyOrleansBundle\Entity\Evenement $evenement
     *
     * @return Media
     */
    public function setEvenement(\MyOrleansBundle\Entity\Evenement $evenement = null)
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return \MyOrleansBundle\Entity\Evenement
     */
    public function getEvenement()
    {
        return $this->evenement;
    }

    /**
     * Set partenaire
     *
     * @param \MyOrleansBundle\Entity\Partenaire $partenaire
     *
     * @return Media
     */
    public function setPartenaire(\MyOrleansBundle\Entity\Partenaire $partenaire = null)
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    /**
     * Get partenaire
     *
     * @return \MyOrleansBundle\Entity\Partenaire
     */
    public function getPartenaire()
    {
        return $this->partenaire;
    }

    /**
     * Set service
     *
     * @param \MyOrleansBundle\Entity\Service $service
     *
     * @return Media
     */
    public function setService(\MyOrleansBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \MyOrleansBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set pack
     *
     * @param \MyOrleansBundle\Entity\Pack $pack
     *
     * @return Media
     */
    public function setPack(\MyOrleansBundle\Entity\Pack $pack = null)
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * Get pack
     *
     * @return \MyOrleansBundle\Entity\Pack
     */
    public function getPack()
    {
        return $this->pack;
    }

    /**
     * @return mixed
     */
    public function getFlats()
    {
        return $this->flats;
    }

    /**
     * @param mixed $flats
     */
    public function setFlats($flats)
    {
        $this->flats = $flats;
    }

    /**
     * @return mixed
     */
    public function getResidences()
    {
        return $this->residences;
    }

    /**
     * @param mixed $residences
     */
    public function setResidences($residences)
    {
        $this->residences = $residences;
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
     * @return mixed
     */
    public function getTypeMedia()
    {
        return $this->typeMedia;
    }

    /**
     * @param mixed $typeMedia
     */
    public function setTypeMedia($typeMedia)
    {
        $this->typeMedia = $typeMedia;
    }



    /**
     * Add flat
     *
     * @param \MyOrleansBundle\Entity\Flat $flat
     *
     * @return Media
     */
    public function addFlat(\MyOrleansBundle\Entity\Flat $flat)
    {
        $this->flats[] = $flat;

        return $this;
    }

    /**
     * Remove flat
     *
     * @param \MyOrleansBundle\Entity\Flat $flat
     */
    public function removeFlat(\MyOrleansBundle\Entity\Flat $flat)
    {
        $this->flats->removeElement($flat);
    }

    /**
     * Add residence
     *
     * @param \MyOrleansBundle\Entity\Residence $residence
     *
     * @return Media
     */
    public function addResidence(\MyOrleansBundle\Entity\Residence $residence)
    {
        $this->residences[] = $residence;
        return $this;
    }

    /**
     * Remove residence
     *
     * @param \MyOrleansBundle\Entity\Residence $residence
     */
    public function removeResidence(\MyOrleansBundle\Entity\Residence $residence)
    {
        $this->residences->removeElement($residence);
    }

    /**
     * Add article
     *
     * @param \MyOrleansBundle\Entity\Article $article
     *
     * @return Media
     */
    public function addArticle(\MyOrleansBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \MyOrleansBundle\Entity\Article $article
     */
    public function removeArticle(\MyOrleansBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }


    /**
     * @return mixed
     */
    public function getCollaborateur()
    {
        return $this->collaborateur;
    }

    /**
     * @param mixed $collaborateur
     */
    public function setCollaborateur($collaborateur)
    {
        $this->collaborateur = $collaborateur;
    }

    /**
     * @return mixed
     */
    public function getCategoriePresta()
    {
        return $this->categorie_presta;
    }

    /**
     * @param mixed $categorie_presta
     */
    public function setCategoriePresta($categorie_presta)
    {
        $this->categorie_presta = $categorie_presta;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $media
     *
     * @return Media
     */
    public function setMediaFile(File $media = null)
    {
        $this->mediaFile = $media;

        if ($media)
            $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * @return File|null
     */
    public function getMediaFile()
    {
        return $this->mediaFile;
    }

    /**
     * @param string $mediaName
     *
     * @return Media
     */
    public function setMediaName($mediaName)
    {
        $this->mediaName = $mediaName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMediaName()
    {
        return $this->mediaName;
    }
}
