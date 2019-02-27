<?php

namespace MyToursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Flat
 *
 * @ORM\Table(name="flat")
 * @ORM\Entity(repositoryClass="MyToursBundle\Repository\FlatRepository")
 * @UniqueEntity(fields="reference", message="Une reference existe déjà avec ce nom.")
 */
class Flat
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
     * @Assert\Type(
     *     type="string",
     *     message="La référence saisie n'est pas correcte."
     * )
     * @ORM\Column(name="reference", type="string", length=45, nullable=true, unique=true)
     */
    private $reference;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="Le prix saisi n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le prix ne peut pas être inférieur à 0€",
     * )
     * @ORM\Column(name="prix", type="integer", nullable=false)
     */
    private $prix;

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
     * @ORM\Column(name="surface", type="float", nullable=false)
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
     * @ORM\Column(name="surface_sejour", type="float", nullable=true)
     */
    private $surfaceSejour;

    /**
     * @var string
     *
     * @ORM\Column(name="exposition_sejour", type="text", nullable=true)
     */
    private $expositionSejour;

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
     * @var bool
     *
     * @ORM\Column(name="statut", type="boolean", length=10, nullable=true)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="TypeBien", inversedBy="flats")
     */
    private $typeBien;

    /**
     * @ORM\ManyToOne(targetEntity="TypeLogement", inversedBy="flats")
     */
    private $typeLogement;

    /**
     * @ORM\ManyToOne(targetEntity="Residence", inversedBy="flats", cascade={"persist"})
     */
    private $residence;

    /**
     * @ORM\ManyToMany(targetEntity="Media", cascade={"persist"})
     * @JoinTable(name="flat_media")
     * @Assert\Valid()
     */
    private $medias;

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
     *     message="La saisie n'est pas correcte."
     * )
     * @ORM\Column(name="date_livraison", type="string", nullable=true)
     */
    private $dateLivraison;

    /**
     * @var string
     *
     * @ORM\Column(name="menuiserie", type="text", nullable=true)
     */
    private $menuiserie;

    /**
     * @var string
     *
     * @ORM\Column(name="chauffage", type="text", nullable=true)
     */
    private $chauffage;

    /**
     * @var string
     *
     * @ORM\Column(name="solSejour", type="text", nullable=true)
     */
    private $solSejour;

    /**
     * @var string
     *
     * @ORM\Column(name="solSdb", type="text", nullable=true)
     */
    private $solSdb;

    /**
     * @var string
     *
     * @ORM\Column(name="solChambre", type="text", nullable=true)
     */
    private $solChambre;

    /**
     * @var string
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "Saisie limitée à 5 caractères"
     * )
     *
     * @ORM\Column(name="etage", type="string", length=5, nullable=true)
     */
    private $etage;

    /**
     * @return string
     */
    public function getEtage()
    {
        return $this->etage;
    }

    /**
     * @param string $etage
     * @return Flat
     */
    public function setEtage($etage)
    {
        $this->etage = $etage;
        return $this;
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
     * @return Flat
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
     * @return Flat
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
     * @return Flat
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
     * @return Flat
     */
    public function setSurfaceTerrain($surfaceTerrain)
    {
        $this->surfaceTerrain = $surfaceTerrain;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateLivraison()
    {
        return $this->dateLivraison;
    }

    /**
     * @param string $dateLivraison
     * @return Flat
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->dateLivraison = $dateLivraison;
        return $this;
    }

    /**
     * @return string
     */
    public function getMenuiserie()
    {
        return $this->menuiserie;
    }

    /**
     * @param string $menuiserie
     * @return Flat
     */
    public function setMenuiserie($menuiserie)
    {
        $this->menuiserie = $menuiserie;
        return $this;
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
     * @return Flat
     */
    public function setChauffage($chauffage)
    {
        $this->chauffage = $chauffage;
        return $this;
    }

    /**
     * @return string
     */
    public function getSolSejour()
    {
        return $this->solSejour;
    }

    /**
     * @param string $solSejour
     * @return Flat
     */
    public function setSolSejour($solSejour)
    {
        $this->solSejour = $solSejour;
        return $this;
    }

    /**
     * @return string
     */
    public function getSolSdb()
    {
        return $this->solSdb;
    }

    /**
     * @param string $solSdb
     * @return Flat
     */
    public function setSolSdb($solSdb)
    {
        $this->solSdb = $solSdb;
        return $this;
    }

    /**
     * @return string
     */
    public function getSolChambre()
    {
        return $this->solChambre;
    }

    /**
     * @param string $solChambre
     * @return Flat
     */
    public function setSolChambre($solChambre)
    {
        $this->solChambre = $solChambre;
        return $this;
    }

    /**
     * @return string
     */
    public function getRevetementMur()
    {
        return $this->revetementMur;
    }

    /**
     * @param string $revetementMur
     * @return Flat
     */
    public function setRevetementMur($revetementMur)
    {
        $this->revetementMur = $revetementMur;
        return $this;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="revetementMur", type="text", nullable=true)
     */
    private $revetementMur;

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
     * Set reference
     *
     * @param string $reference
     *
     * @return Flat
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
     * Set prix
     *
     * @param integer $prix
     *
     * @return Flat
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set surface
     *
     * @param float $surface
     *
     * @return Flat
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface
     *
     * @return float
     */
    public function getSurface()
    {
        return $this->surface;
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
     * @return mixed
     */
    public function getTypeBien()
    {
        return $this->typeBien;
    }

    /**
     * @param mixed $typeBien
     */
    public function setTypeBien($typeBien)
    {
        $this->typeBien = $typeBien;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
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
     */
    public function setTypeLogement($typeLogement)
    {
        $this->typeLogement = $typeLogement;
    }

    /**
     * Set residence
     *
     * @param \MyToursBundle\Entity\Residence $residence
     *
     * @return Flat
     */
    public function setResidence(\MyToursBundle\Entity\Residence $residence = null)
    {
        $this->residence = $residence;

        return $this;
    }

    /**
     * Get residence
     *
     * @return \MyToursBundle\Entity\Residence
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
     * Add media
     *
     * @param \MyToursBundle\Entity\Media $media
     *
     * @return Flat
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


    /**
     * Set surfaceSejour
     *
     * @param float $surfaceSejour
     *
     * @return Flat
     */
    public function setSurfaceSejour($surfaceSejour)
    {
        $this->surfaceSejour = $surfaceSejour;

        return $this;
    }

    /**
     * Get surfaceSejour
     *
     * @return float
     */
    public function getSurfaceSejour()
    {
        return $this->surfaceSejour;
    }

    /**
     * Set expositionSejour
     *
     * @param string $expositionSejour
     *
     * @return Flat
     */
    public function setExpositionSejour($expositionSejour)
    {
        $this->expositionSejour = $expositionSejour;

        return $this;
    }

    /**
     * Get expositionSejour
     *
     * @return string
     */
    public function getExpositionSejour()
    {
        return $this->expositionSejour;
    }

    /**
     * Set stationnement
     *
     * @param string $stationnement
     *
     * @return Flat
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
}
