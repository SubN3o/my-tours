<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\ORM\Mapping\JoinTable;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Residence
 *
 * @ORM\Table(name="residence")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\ResidenceRepository")
 */
class Residence
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
     * @ORM\Column(name="nom", type="string", length=45, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=45, nullable=true)
     */
    private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="code_postal", type="integer", nullable=true)
     */
    private $codePostal;

    /**
     * @ORM\ManyToOne(targetEntity="Ville", inversedBy="residences", cascade={"persist"}, fetch="EAGER")
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity="Quartier", inversedBy="residences", cascade={"persist"}, fetch="EAGER")
     */
    private $quartier;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="date_livraison", type="string", nullable=true)
     */
    private $dateLivraison;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_total_logements", type="integer", nullable=true)
     */
    private $nbTotalLogements;

    /**
     * @var float
     *
     * @ORM\Column(name="note_transports", type="float", nullable=true)
     */
    private $noteTransports;

    /**
     * @var float
     *
     * @ORM\Column(name="note_commerces", type="float", nullable=true)
     */
    private $noteCommerces;

    /**
     * @var float
     *
     * @ORM\Column(name="note_services", type="float", nullable=true)
     */
    private $noteServices;

    /**
     * @var float
     *
     * @ORM\Column(name="note_esthetisme", type="float", nullable=true)
     */
    private $noteEsthetisme;

    /**
     * @var bool
     *
     * @ORM\Column(name="favoris", type="boolean", nullable=true)
     */
    private $favoris;

    /**
     * @var string
     *
     * @ORM\Column(name="accroche", type="string", nullable=true)
     */
    private $accroche;

    /**
     * @var bool
     *
     * @ORM\Column(name="eligibilite_pinel", type="boolean", nullable=true)
     *
     */
    private $eligibilitePinel;

    /**
     * @var bool
     *
     * @ORM\Column(name="affichage_prix", type="boolean", nullable=false)
     */
    private $affichagePrix;

    /**
     * @ORM\ManyToMany(targetEntity="Media", cascade={"persist"})
     * @JoinTable(name="residence_media")
     *
     */
    private $medias;

    /**
     * @ORM\OneToMany(targetEntity="Flat", mappedBy="residence", cascade={"all"}, fetch="EAGER")
     */
    private $flats;

    /**
     * @ORM\OneToMany(targetEntity="CategoriePresta", mappedBy="residence", cascade={"all"}, fetch="EAGER")
     */
    private $categoriePrestas;


    /**
     * @var string
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(name="slug", type="string")
     */
    private $slug;


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
     * @return Residence
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Residence
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return Residence
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return int
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }


    //Getter Setter $Ville


    /**
     * @return string
     */
    public function getQuartier()
    {
        return $this->quartier;
    }

    /**
     * @param string $quartier
     */
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Residence
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Residence
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set dateLivraison
     *
     * @param string $dateLivraison
     *
     * @return Residence
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    /**
     * Get dateLivraison
     *
     * @return string
     */
    public function getDateLivraison()
    {
        return $this->dateLivraison;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Residence
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set nbTotalLogements
     *
     * @param integer $nbTotalLogements
     *
     * @return Residence
     */
    public function setNbTotalLogements($nbTotalLogements)
    {
        $this->nbTotalLogements = $nbTotalLogements;

        return $this;
    }

    /**
     * Get nbTotalLogements
     *
     * @return int
     */
    public function getNbTotalLogements()
    {
        return $this->nbTotalLogements;
    }

    /**
     * Set noteTransports
     *
     * @param float $noteTransports
     *
     * @return Residence
     */
    public function setNoteTransports($noteTransports)
    {
        $this->noteTransports = $noteTransports;

        return $this;
    }

    /**
     * Get noteTransports
     *
     * @return float
     */
    public function getNoteTransports()
    {
        return $this->noteTransports;
    }

    /**
     * Set noteCommerces
     *
     * @param float $noteCommerces
     *
     * @return Residence
     */
    public function setNoteCommerces($noteCommerces)
    {
        $this->noteCommerces = $noteCommerces;

        return $this;
    }

    /**
     * Get noteCommerces
     *
     * @return float
     */
    public function getNoteCommerces()
    {
        return $this->noteCommerces;
    }

    /**
     * Set noteServices
     *
     * @param float $noteServices
     *
     * @return Residence
     */
    public function setNoteServices($noteServices)
    {
        $this->noteServices = $noteServices;

        return $this;
    }

    /**
     * Get noteServices
     *
     * @return float
     */
    public function getNoteServices()
    {
        return $this->noteServices;
    }

    /**
     * Set noteEsthetisme
     *
     * @param float $noteEsthetisme
     *
     * @return Residence
     */
    public function setNoteEsthetisme($noteEsthetisme)
    {
        $this->noteEsthetisme = $noteEsthetisme;

        return $this;
    }

    /**
     * Get noteEsthetisme
     *
     * @return float
     */
    public function getNoteEsthetisme()
    {
        return $this->noteEsthetisme;
    }

    /**
     * @return mixed
     */
    public function getAccroche()
    {
        return $this->accroche;
    }

    /**
     * @param mixed $accroche
     */
    public function setAccroche($accroche)
    {
        $this->accroche = $accroche;
    }


    public function addMedia(Media $media)
    {
        $media->addResidence($this); // synchronously updating inverse side
        $this->medias[] = $media;
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
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->flats = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Add flat
     *
     * @param \MyOrleansBundle\Entity\Flat $flat
     *
     * @return Residence
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
     * Get flats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFlats()
    {
        return $this->flats;
    }

    /**
     * @return mixed
     */
    public function getCategoriePrestas()
    {
        return $this->categoriePrestas;
    }

    /**
     * @param mixed $categoriePrestas
     */
    public function setCategoriePrestas($categoriePrestas)
    {
        $this->categoriePrestas = $categoriePrestas;
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
     * Add categoriePresta
     *
     * @param \MyOrleansBundle\Entity\CategoriePresta $categoriePresta
     *
     * @return Residence
     */
    public function addCategoriePresta(\MyOrleansBundle\Entity\CategoriePresta $categoriePresta)
    {
        $this->categoriePrestas[] = $categoriePresta;

        return $this;
    }

    /**
     * Remove categoriePresta
     *
     * @param \MyOrleansBundle\Entity\CategoriePresta $categoriePresta
     */
    public function removeCategoriePresta(\MyOrleansBundle\Entity\CategoriePresta $categoriePresta)
    {
        $this->categoriePrestas->removeElement($categoriePresta);
    }


    /**
     * Set ville
     *
     * @param \MyOrleansBundle\Entity\Ville $ville
     *
     * @return Residence
     */
    public function setVille(\MyOrleansBundle\Entity\Ville $ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \MyOrleansBundle\Entity\Ville
     */
    public function getVille()
    {
        return $this->ville;
    }

 /*   *
     * @param bool $favoris
     * @return Residence
     */
    public function setFavoris(bool $favoris): Residence
    {
        $this->favoris = $favoris;
        return $this;
    }

    /**
     * @param bool $eligibilitePinel
     * @return Residence
     */
    public function setEligibilitePinel(bool $eligibilitePinel): Residence
    {
        $this->eligibilitePinel = $eligibilitePinel;
        return $this;
    }

    /**
     * @param bool $affichagePrix
     * @return Residence
     */
    public function setAffichagePrix(bool $affichagePrix): Residence
    {
        $this->affichagePrix = $affichagePrix;
        return $this;
    }




    /**
     * Get favoris
     *
     * @return boolean
     */
    public function getFavoris()
    {
        return $this->favoris;
    }

    /**
     * Get eligibilitePinel
     *
     * @return boolean
     */
    public function getEligibilitePinel()
    {
        return $this->eligibilitePinel;
    }

    /**
     * Get affichagePrix
     *
     * @return boolean
     */
    public function getAffichagePrix()
    {
        return $this->affichagePrix;

    }


    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Residence
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
