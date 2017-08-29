<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\ServiceRepository")
 */
class Service
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
     *     message="Le type saisi n'est pas correcte."
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 45,
     *      minMessage = "Le type saisi est court.",
     *      maxMessage = "Le type saisi est long."
     * )
     *
     * @ORM\Column(name="type", type="string", length=45)
     */
    private $type;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     * @ORM\Column(name="description", type="text")
     */
    private $description;


    /**
     * @ORM\OneToOne(targetEntity="Media", inversedBy="service", cascade={"persist"})
     * @Assert\Valid()
     */
    private $media;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="La saisie n'est pas correcte."
     * )
     * @ORM\Column(name="tri", type="integer", nullable=true)
     */
    private $tri;

    /**
     * @var string
     *  @Assert\Url(
     *     message = "L'URL saisie n'est pas correcte",
     *     protocols = {"http", "https", "ftp"}
     * )
     * @ORM\Column(name="lien_youtube", type="string", nullable=true)
     */
    private $lienYoutube;

    /**
     * @return int
     */
    public function getTri()
    {
        return $this->tri;
    }

    /**
     * @param int $tri
     * @return Service
     */
    public function setTri($tri)
    {
        $this->tri = $tri;
        return $this;
    }

    /**
     * @return string
     */
    public function getLienYoutube()
    {
        return $this->lienYoutube;
    }

    /**
     * @param string $lienYoutube
     * @return Service
     */
    public function setLienYoutube($lienYoutube)
    {
        $this->lienYoutube = $lienYoutube;
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
     * Set type
     *
     * @param string $type
     *
     * @return Service
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Service
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
     * Set media
     *
     * @param \MyOrleansBundle\Entity\Media $media
     *
     * @return Service
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add media
     *
     * @param \MyOrleansBundle\Entity\Media $media
     *
     * @return Service
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
