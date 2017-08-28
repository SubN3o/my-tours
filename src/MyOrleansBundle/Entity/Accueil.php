<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Accueil
 *
 * @ORM\Table(name="accueil")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\AccueilRepository")
 */
class Accueil
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
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Le texte saisi est dépasse les 255 charactères."
     * )
     * @ORM\Column(name="presentation", type="string", length=255)
     */
    private $presentation;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     * @ORM\Column(name="mentions", type="text")
     */
    private $mentions;

    /**
     * @ORM\ManyToMany(targetEntity="Media", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinTable(name="accueil_media")
     * @Assert\Valid()
     */
    private $medias;

    /**
     * @return mixed
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $medias
     * @return Accueil
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;
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
     * Set presentation
     *
     * @param string $presentation
     *
     * @return Accueil
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set mentions
     *
     * @param string $mentions
     *
     * @return Accueil
     */
    public function setMentions($mentions)
    {
        $this->mentions = $mentions;

        return $this;
    }

    /**
     * Get mentions
     *
     * @return string
     */
    public function getMentions()
    {
        return $this->mentions;
    }
}

