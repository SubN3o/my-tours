<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Chiffre
 *
 * @ORM\Table(name="chiffre")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\ChiffreRepository")
 * @UniqueEntity(fields="tri", message="Ce tri existe déjà.")
 */
class Chiffre
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
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur saisie n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La valeur ne peut pas être inférieur à 0",
     * )
     *
     * @ORM\Column(name="valeur", type="integer")
     */
    private $valeur;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="La saisie n'est pas correcte."
     * )
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Saisissez une valeur supérieure ou égale à 1",
     * )
     * @ORM\Column(name="tri", type="integer", nullable=true, unique=true)
     */
    private $tri;

    /**
     * @return int
     */
    public function getTri()
    {
        return $this->tri;
    }

    /**
     * @param int $tri
     * @return Chiffre
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Chiffre
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
     * Set valeur
     *
     * @param integer $valeur
     *
     * @return Chiffre
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return int
     */
    public function getValeur()
    {
        return $this->valeur;
    }
}

