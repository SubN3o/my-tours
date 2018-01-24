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
     * @ORM\ManyToMany(targetEntity="Evenement", cascade={"persist"})
     */
    private $evenements;

    /**
     * @ORM\OneToOne(targetEntity="Partenaire", mappedBy="media")
     */
    private $partenaire;

    /**
     * @ORM\ManyToMany(targetEntity="Service", cascade={"persist"})
     */
    private $services;

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
     * @Assert\File(
     *     maxSize = "15M",
     *     maxSizeMessage="Le fichier est trop volumineux. Sa taille ne doit pas dépasser 15 MB",
     *     mimeTypes = {"application/pdf",
     *     "application/x-pdf",
     *     "image/jpg",
     *     "image/jpeg",
     *     "image/png",
     *     "video/mp4",
     *     "video/ogg",
     *     "video/webm"},
     *     mimeTypesMessage = "Veuillez télécharger un fichier pdf, x-pdf, jpg, jpeg, png, mp4, ogg ou webm valide"
     * )
     *
     * @var File
     */
    private $mediaFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $mediaName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="Temoignage", mappedBy="media")
     */
    private $temoignage;

    /**
     * @ORM\ManyToMany(targetEntity="Accueil", cascade={"persist"})
     */
    private $accueils;

    /**
     * @ORM\OneToOne(targetEntity="Realisation", mappedBy="media")
     */
    private $realisation;

    /**
     * @ORM\ManyToMany(targetEntity="Ancien", cascade={"persist"})
     */
    private $anciens;

    /**
     * @ORM\ManyToMany(targetEntity="Location", cascade={"persist"})
     */
    private $locations;

    /**
     * @return mixed
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param mixed $locations
     * @return Media
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnciens()
    {
        return $this->anciens;
    }

    /**
     * @param mixed $anciens
     * @return Media
     */
    public function setAnciens($anciens)
    {
        $this->anciens = $anciens;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccueils()
    {
        return $this->accueils;
    }

    /**
     * @param mixed $accueils
     * @return Media
     */
    public function setAccueils($accueils)
    {
        $this->accueils = $accueils;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemoignage()
    {
        return $this->temoignage;
    }

    /**
     * @param mixed $temoignage
     * @return Media
     */
    public function setTemoignage($temoignage)
    {
        $this->temoignage = $temoignage;
        return $this;
    }

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
        $this->evenements = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->anciens = new ArrayCollection();
        $this->locations = new ArrayCollection();
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
     * Get realisation
     *
     * @return \MyOrleansBundle\Entity\Realisation
     */
    public function getRealisation()
    {
        return $this->realisation;
    }

    /**
     * Set realisation
     *
     * @param \MyOrleansBundle\Entity\Realisation $realisation
     *
     * @return Media
     */
    public function setRealisation(\MyOrleansBundle\Entity\Realisation $realisation = null)
    {
        $this->realisation = $realisation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param mixed $services
     */
    public function setServices($services)
    {
        $this->services = $services;
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
    public function getEvenements()
    {
        return $this->evenements;
    }

    /**
     * @param mixed $evenements
     */
    public function setEvenements($evenements)
    {
        $this->evenements = $evenements;
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
     * Add ancien
     *
     * @param \MyOrleansBundle\Entity\Ancien $ancien
     *
     * @return Media
     */
    public function addAncien(\MyOrleansBundle\Entity\Ancien $ancien)
    {
        $this->anciens[] = $ancien;

        return $this;
    }

    /**
     * Remove ancien
     *
     * @param \MyOrleansBundle\Entity\Ancien $ancien
     */
    public function removeAncien(\MyOrleansBundle\Entity\Ancien $ancien)
    {
        $this->anciens->removeElement($ancien);
    }

    /**
     * Add location
     *
     * @param \MyOrleansBundle\Entity\Location $location
     *
     * @return Media
     */
    public function addLocation(\MyOrleansBundle\Entity\Location $location)
    {
        $this->locations[] = $location;

        return $this;
    }

    /**
     * Remove location
     *
     * @param \MyOrleansBundle\Entity\Location $location
     */
    public function removeLocation(\MyOrleansBundle\Entity\Location $location)
    {
        $this->locations->removeElement($location);
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
