<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Partenaire
 *
 * @ORM\Table(name="partenaire")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\PartenaireRepository")
 */
class Partenaire
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
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 45,
     *      minMessage = "Le nom saisi est court.",
     *      maxMessage = "Le nom saisi est long."
     * )
     * @ORM\Column(name="nom", type="string", length=45)
     */
    private $nom;

    /**
     * @var string
     * @Assert\Url(
     *     message = "L'URL saisie n'est pas correcte",
     *     protocols = {"http", "https", "ftp"}
     * )
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\OneToOne(targetEntity="Media", inversedBy="partenaire", cascade={"persist"})
     * @Assert\Valid()
     */
    private $media;

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
     * @ORM\Column(name="tri", type="integer", nullable=true)
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
     * @return Partenaire
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
     * @return Partenaire
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
     * Set url
     *
     * @param string $url
     *
     * @return Partenaire
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set media
     *
     * @param \MyOrleansBundle\Entity\Media $media
     *
     * @return Partenaire
     */
    public function setMedia(\MyOrleansBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \MyOrleansBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }
}
