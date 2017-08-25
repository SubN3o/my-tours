<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     *
     * @ORM\Column(name="presentation", type="string", length=255)
     */
    private $presentation;

    /**
     * @var string
     *
     * @ORM\Column(name="mentions", type="string", length=255)
     */
    private $mentions;


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

