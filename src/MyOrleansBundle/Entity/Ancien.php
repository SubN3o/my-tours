<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\JoinTable;


/**
 * Ancien
 *
 * @ORM\Table(name="ancien")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\AncienRepository")
 * @UniqueEntity(fields="reference", message="Une reference existe déjà avec ce nom.")
 */
class Ancien
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
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="Le prix saisi n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le prix ne peut pas être inférieur à 0€",
     * )
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;


    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="Les charges annuelles saisies ne sont pas correctes."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Les charges annuelles ne peuvent pas être inférieures à 0€",
     * )
     * @ORM\Column(name="chargesAnnuelles", type="integer", nullable=true)
     */
    private $chargesAnnuelles;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="La taxe foncière saisie n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La taxe foncière ne peut pas être inférieur à 0€",
     * )
     * @ORM\Column(name="taxeFonciere", type="integer", nullable=true)
     */
    private $taxeFonciere;

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
     * @ORM\ManyToOne(targetEntity="Ville", inversedBy="anciens", cascade={"persist"}, fetch="EAGER")
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity="TypeBien", inversedBy="anciens")
     */
    private $typeBien;

    /**
     * @ORM\ManyToOne(targetEntity="TypeLogement", inversedBy="anciens")
     */
    private $typeLogement;

    /**
     * @ORM\ManyToMany(targetEntity="Media", cascade={"persist"})
     * @JoinTable(name="ancien_media")
     * @Assert\Valid()
     */
    private $medias;

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
     * @var bool
     *
     * @ORM\Column(name="copropriete", type="boolean", length=10, nullable=true)
     */
    private $copropriete;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="Le nombre de lots saisi n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le nombre de lots ne peut pas être inférieur à 0",
     * )
     * @ORM\Column(name="nbLots", type="integer", nullable=true)
     */
    private $nbLots;

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
     * @return bool
     */
    public function getCopropriete()
    {
        return $this->copropriete;
    }

    /**
     * @param bool $copropriete
     */
    public function setCopropriete(bool $copropriete)
    {
        $this->copropriete = $copropriete;
    }

    /**
     * @return int
     */
    public function getNbLots()
    {
        return $this->nbLots;
    }

    /**
     * @param int $nbLots
     * @return Ancien
     */
    public function setNbLots($nbLots)
    {
        $this->nbLots = $nbLots;
        return $this;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Ancien
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
     * @return int
     */
    public function getFraisAgence()
    {
        return $this->fraisAgence;
    }

    /**
     * @param int $fraisAgence
     * @return Ancien
     */
    public function setFraisAgence($fraisAgence)
    {
        $this->fraisAgence = $fraisAgence;
        return $this;
    }

    /**
     * @return int
     */
    public function getChargesAnnuelles()
    {
        return $this->chargesAnnuelles;
    }

    /**
     * @param int $chargesAnnuelles
     * @return Ancien
     */
    public function setChargesAnnuelles($chargesAnnuelles)
    {
        $this->chargesAnnuelles = $chargesAnnuelles;
        return $this;
    }

    /**
     * @return int
     */
    public function getTaxeFonciere()
    {
        return $this->taxeFonciere;
    }

    /**
     * @param int $taxeFonciere
     * @return Ancien
     */
    public function setTaxeFonciere($taxeFonciere)
    {
        $this->taxeFonciere = $taxeFonciere;
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
     * @return Ancien
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
     * @return Ancien
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
     * @return Ancien
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
     * @return Ancien
     */
    public function setSurfaceTerrain($surfaceTerrain)
    {
        $this->surfaceTerrain = $surfaceTerrain;
        return $this;
    }

    /**
     * @return string
     */
    public function getStationnement()
    {
        return $this->stationnement;
    }

    /**
     * @param string $stationnement
     * @return Ancien
     */
    public function setStationnement($stationnement)
    {
        $this->stationnement = $stationnement;
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
     * @return Ancien
     */
    public function setChauffage($chauffage)
    {
        $this->chauffage = $chauffage;
        return $this;
    }

    /**
     * Set surface
     *
     * @param integer $surface
     *
     * @return Ancien
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
     * Set reference
     *
     * @param string $reference
     *
     * @return Ancien
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
     * @return Ancien
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
     * @return Ancien
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
     * @return Ancien
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
     * @return Ancien
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
     * @return Ancien
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
     * @return Ancien
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
     * @return Ancien
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
     * @return Ancien
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;
        return $this;
    }

    /**
     * Add media
     *
     * @param \MyOrleansBundle\Entity\Media $media
     *
     * @return Ancien
     */
    public function addMedia(\MyOrleansBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

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
}

