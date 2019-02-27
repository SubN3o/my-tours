<?php

namespace MyToursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\JoinTable;


/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity(repositoryClass="MyToursBundle\Repository\LocationRepository")
 * @UniqueEntity(fields="reference", message="Une reference existe déjà avec ce nom.")
 */
class Location
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
     * @var bool
     *
     * @ORM\Column(name="statut", type="boolean", length=10, nullable=true)
     */
    private $statut;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="Le loyer saisi n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le loyer ne peut pas être inférieur à 0€",
     * )
     * @ORM\Column(name="loyer", type="integer")
     */
    private $loyer;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="La provision saisie n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La provision ne peut pas être inférieur à 0€",
     * )
     * @ORM\Column(name="provision", type="integer", nullable=true)
     */
    private $provision;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="Le dépot saisi n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le dépot ne peut pas être inférieur à 0€",
     * )
     * @ORM\Column(name="depot", type="integer")
     */
    private $depot;

    /**
     * @var float
     * @Assert\Type(
     *     type="float",
     *     message="La surface saisie n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La surface saisie ne peut pas être inférieur à 0",
     * )
     * @ORM\Column(name="surface", type="float")
     */
    private $surface;

    /**
     * @var float
     * @Assert\Type(
     *     type="float",
     *     message="La surface saisie n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La surface saisie ne peut pas être inférieur à 0",
     * )
     *
     * @ORM\Column(name="surface_balcon", type="float", nullable=true)
     */
    private $surfaceBalcon;

    /**
     * @var float
     * @Assert\Type(
     *     type="float",
     *     message="La surface saisie n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La surface saisie ne peut pas être inférieur à 0",
     * )
     *
     * @ORM\Column(name="surface_terrasse", type="float", nullable=true)
     */
    private $surfaceTerrasse;

    /**
     * @var float
     * @Assert\Type(
     *     type="float",
     *     message="La surface saisie n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La surface saisie ne peut pas être inférieur à 0",
     * )
     *
     * @ORM\Column(name="surface_jardin", type="float", nullable=true)
     */
    private $surfaceJardin;

    /**
     * @var float
     * @Assert\Type(
     *     type="float",
     *     message="La surface saisie n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La surface saisie ne peut pas être inférieur à 0",
     * )
     *
     * @ORM\Column(name="surface_terrain", type="float", nullable=true)
     */
    private $surfaceTerrain;
    
    /**
     * @var string
     * @Assert\Type(
     *     type="string",
     *     message="La référence saisie n'est pas correcte."
     * )
     * @ORM\Column(name="reference", type="string", length=45, unique=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="ges", type="string", length=6)
     */
    private $ges;

    /**
     * @var string
     *
     * @ORM\Column(name="energie", type="string", length=6)
     */
    private $energie;

    /**
     * @var string
     *  @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     * @ORM\Column(name="accroche", type="string", nullable=true)
     */
    private $accroche;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Ville", inversedBy="locations", cascade={"persist"}, fetch="EAGER")
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity="TypeBien", inversedBy="locations")
     */
    private $typeBien;

    /**
     * @ORM\ManyToOne(targetEntity="TypeLogement", inversedBy="locations")
     */
    private $typeLogement;

    /**
     * @ORM\ManyToMany(targetEntity="Media", cascade={"persist"})
     * @JoinTable(name="location_media")
     * @Assert\Valid()
     */
    private $medias;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     * @Assert\Type(
     *    type="string",
     *    message="La saisie n'est pas correcte."
     * )
     * @ORM\Column(name="stationnement", type="text", nullable=true)
     */
    private $stationnement;

    /**
     * @var string
     *
     * @ORM\Column(name="chauffage", type="text", nullable=true)
     */
    private $chauffage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return bool
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param bool $statut
     */
    public function setStatut(bool $statut)
    {
        $this->statut = $statut;
    }

    /**
     * Set loyer
     *
     * @param integer $loyer
     *
     * @return Location
     */
    public function setLoyer($loyer)
    {
        $this->loyer = $loyer;

        return $this;
    }

    /**
     * Get loyer
     *
     * @return int
     */
    public function getLoyer()
    {
        return $this->loyer;
    }

    /**
     * Set provision
     *
     * @param integer $provision
     *
     * @return Location
     */
    public function setProvision($provision)
    {
        $this->provision = $provision;

        return $this;
    }

    /**
     * Get provision
     *
     * @return int
     */
    public function getProvision()
    {
        return $this->provision;
    }

    /**
     * Set depot
     *
     * @param integer $depot
     *
     * @return Location
     */
    public function setDepot($depot)
    {
        $this->depot = $depot;

        return $this;
    }

    /**
     * Get depot
     *
     * @return int
     */
    public function getDepot()
    {
        return $this->depot;
    }

    /**
     * Set surface
     *
     * @param integer $surface
     *
     * @return Location
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface
     *
     * @return int
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * @return float
     */
    public function getSurfaceBalcon()
    {
        return $this->surfaceBalcon;
    }

    /**
     * @param float $surfaceBalcon
     * @return Location
     */
    public function setSurfaceBalcon($surfaceBalcon)
    {
        $this->surfaceBalcon = $surfaceBalcon;
        return $this;
    }

    /**
     * @return float
     */
    public function getSurfaceTerrasse()
    {
        return $this->surfaceTerrasse;
    }

    /**
     * @param float $surfaceTerrasse
     * @return Location
     */
    public function setSurfaceTerrasse($surfaceTerrasse)
    {
        $this->surfaceTerrasse = $surfaceTerrasse;
        return $this;
    }

    /**
     * @return float
     */
    public function getSurfaceJardin()
    {
        return $this->surfaceJardin;
    }

    /**
     * @param float $surfaceJardin
     * @return Location
     */
    public function setSurfaceJardin($surfaceJardin)
    {
        $this->surfaceJardin = $surfaceJardin;
        return $this;
    }

    /**
     * @return float
     */
    public function getSurfaceTerrain()
    {
        return $this->surfaceTerrain;
    }

    /**
     * @param float $surfaceTerrain
     * @return Location
     */
    public function setSurfaceTerrain($surfaceTerrain)
    {
        $this->surfaceTerrain = $surfaceTerrain;
        return $this;
    }
    
    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Location
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set ges
     *
     * @param string $ges
     *
     * @return Location
     */
    public function setGes($ges)
    {
        $this->ges = $ges;

        return $this;
    }

    /**
     * Get ges
     *
     * @return string
     */
    public function getGes()
    {
        return $this->ges;
    }

    /**
     * Set energie
     *
     * @param string $energie
     *
     * @return Location
     */
    public function setEnergie($energie)
    {
        $this->energie = $energie;

        return $this;
    }

    /**
     * Get energie
     *
     * @return string
     */
    public function getEnergie()
    {
        return $this->energie;
    }

    /**
     * @return string
     */
    public function getAccroche()
    {
        return $this->accroche;
    }

    /**
     * @param string $accroche
     * @return Location
     */
    public function setAccroche($accroche)
    {
        $this->accroche = $accroche;
        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Location
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
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     * @return Location
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypeBien()
    {
        return $this->typeBien;
    }

    /**
     * @param mixed $typeBien
     * @return Location
     */
    public function setTypeBien($typeBien)
    {
        $this->typeBien = $typeBien;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypeLogement()
    {
        return $this->typeLogement;
    }

    /**
     * @param mixed $typeLogement
     * @return Location
     */
    public function setTypeLogement($typeLogement)
    {
        $this->typeLogement = $typeLogement;
        return $this;
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
     * @return Location
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;
        return $this;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Location
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
     * Set stationnement
     *
     * @param string $stationnement
     *
     * @return Location
     */
    public function setStationnement($stationnement)
    {
        $this->stationnement = $stationnement;

        return $this;
    }

    /**
     * Get stationnement
     *
     * @return string
     */
    public function getStationnement()
    {
        return $this->stationnement;
    }

    /**
     * @return string
     */
    public function getChauffage()
    {
        return $this->chauffage;
    }

    /**
     * @param string $chauffage
     * @return Location
     */
    public function setChauffage($chauffage)
    {
        $this->chauffage = $chauffage;
        return $this;
    }

    /**
     * Add media
     *
     * @param \MyToursBundle\Entity\Media $media
     *
     * @return Location
     */
    public function addMedia(\MyToursBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \MyToursBundle\Entity\Media $media
     */
    public function removeMedia(\MyToursBundle\Entity\Media $media)
    {
        $this->medias->removeElement($media);
    }
}

