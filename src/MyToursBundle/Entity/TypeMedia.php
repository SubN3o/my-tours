<?php

namespace MyToursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeMedia
 *
 * @ORM\Table(name="type_media")
 * @ORM\Entity(repositoryClass="MyToursBundle\Repository\TypeMediaRepository")
 */
class TypeMedia
{
    const IMAGE_COVER = 1;
    const IMAGE = 2;
    const IMAGE_HEADER = 3;
    const VIDEO = 4;
    const PLAN = 5;
    const PDF = 6;
    const ICONE = 7;
    const PLAQUETTE = 8;
    const NOTICE = 9;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="typeMedia")
     */
    private $medias;

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
     * @return TypeMedia
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
    }

    /**
     * Add media
     *
     * @param \MyToursBundle\Entity\Media $media
     *
     * @return TypeMedia
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
