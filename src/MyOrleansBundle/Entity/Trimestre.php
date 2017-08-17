<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trimestre
 *
 * @ORM\Table(name="trimestre")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\TrimestreRepository")
 */
class Trimestre
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
     * @ORM\OneToMany(targetEntity="Residence", mappedBy="trimestre", cascade={"all"}, fetch="EAGER")
     */
    private $residences;

    /**
     * @return mixed
     */
    public function getResidences()
    {
        return $this->residences;
    }

    /**
     * @param mixed $residences
     * @return Trimestre
     */
    public function setResidences($residences)
    {
        $this->residences = $residences;
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
     * @return Trimestre
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
}

