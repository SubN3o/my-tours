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
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=60)
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=10)
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=60)
     */
    private $statut;

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
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="residence", type="string", length=255, nullable=true)
     */
    private $residence;

    /**
     * @ORM\ManyToOne(targetEntity="Ville", inversedBy="anciens", cascade={"persist"}, fetch="EAGER")
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity="Quartier", inversedBy="anciens", cascade={"persist"}, fetch="EAGER")
     */
    private $quartier;

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
     * Set objet
     *
     * @param string $objet
     *
     * @return Ancien
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Ancien
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Ancien
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
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
     * Set residence
     *
     * @param string $residence
     *
     * @return Ancien
     */
    public function setResidence($residence)
    {
        $this->residence = $residence;

        return $this;
    }

    /**
     * Get residence
     *
     * @return string
     */
    public function getResidence()
    {
        return $this->residence;
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
    public function getQuartier()
    {
        return $this->quartier;
    }

    /**
     * @param mixed $quartier
     * @return Ancien
     */
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;
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

