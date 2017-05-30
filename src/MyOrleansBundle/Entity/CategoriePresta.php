<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriePresta
 *
 * @ORM\Table(name="categorie_presta")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\CategoriePrestaRepository")
 */
class CategoriePresta
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
     * @ORM\ManyToOne(targetEntity="Media", inversedBy="categorie_prestas")
     */
    private $media;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
