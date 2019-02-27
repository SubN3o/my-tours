<?php

namespace MyToursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeBien
 *
 * @ORM\Table(name="type_bien")
 * @ORM\Entity(repositoryClass="MyToursBundle\Repository\TypeBienRepository")
 */
class TypeBien
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
     * @ORM\Column(name="nom", type="string", length=40)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Flat", mappedBy="typeBien")
     */
    private $flats;

    /**
     * @ORM\OneToMany(targetEntity="Ancien", mappedBy="typeBien")
     */
    private $anciens;

    /**
     * @ORM\OneToMany(targetEntity="Location", mappedBy="typeBien")
     */
    private $locations;

    /**
     * @return mixed
     */
    public function getAnciens()
    {
        return $this->anciens;
    }

    /**
     * @param mixed $anciens
     * @return TypeBien
     */
    public function setAnciens($anciens)
    {
        $this->anciens = $anciens;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param mixed $locations
     * @return TypeBien
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return TypeBien
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
     * Constructor
     */
    public function __construct()
    {
        $this->flats = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add flat
     *
     * @param \MyToursBundle\Entity\Flat $flat
     *
     * @return TypeBien
     */
    public function addFlat(\MyToursBundle\Entity\Flat $flat)
    {
        $this->flats[] = $flat;

        return $this;
    }

    /**
     * Remove flat
     *
     * @param \MyToursBundle\Entity\Flat $flat
     */
    public function removeFlat(\MyToursBundle\Entity\Flat $flat)
    {
        $this->flats->removeElement($flat);
    }
}
