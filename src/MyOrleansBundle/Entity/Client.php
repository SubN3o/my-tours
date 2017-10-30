<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\ClientRepository")
 */
class Client
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
     * @ORM\Column(name="civilite", type="string", length=45, nullable=true)
     */
    private $civilite;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     *
     * @ORM\Column(name="nom", type="string", length=45, nullable=true)
     */
    private $nom;

    /**
     * @var integer
     * @Assert\Type(
     *     type="integer",
     *     message="La saisie n'est pas correcte."
     * )
     *
     * @ORM\Column(name="budget", type="integer", length=45, nullable=true)
     */
    private $budget;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true)
     */
    private $email;

    /**
     * @var string
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     *
     * @ORM\Column(name="telephone", type="string", length=10, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     *
     * @ORM\Column(name="projet", type="string", length=45, nullable=true)
     */
    private $projet;

    /**
     * @var integer
     * @Assert\Type(
     *     type="integer",
     *     message="La saisie n'est pas correcte."
     * )
     *
     * @ORM\Column(name="codePostal", type="integer", length=5, nullable=true)
     */
    private $codePostal;

    /**
     * @var array
     * @Assert\Type(
     *     type="array",
     *     message="La saisie n'est pas correcte."
     * )
     *
     * @ORM\Column(name="typeLogement", type="array", length=45, nullable=true)
     */
    private $typeLogement;


    /**
     * @var int
     *
     *
     * @ORM\Column(name="newsletter", type="integer", nullable=true)
     */
    private $newsletter;

    /**
     * @var string
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     *
     * @ORM\Column(name="sujet", type="string", nullable=true)
     */
    private $sujet;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

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
     * @return string
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * @param string $civilite
     * @return Client
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;
        return $this;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Client
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
     * Set budget
     *
     * @param integer $budget
     *
     * @return Client
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return integer
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Client
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set projet
     *
     * @param string $projet
     *
     * @return Client
     */
    public function setProjet($projet)
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Get projet
     *
     * @return string
     */
    public function getProjet()
    {
        return $this->projet;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return Client
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return int
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set typeLogement
     *
     * @param array $typeLogement
     *
     * @return Client
     */
    public function setTypeLogement($typeLogement)
    {
        $this->typeLogement = $typeLogement;

        return $this;
    }

    /**
     * Get typeLogement
     *
     * @return array
     */
    public function getTypeLogement()
    {
        return $this->typeLogement;
    }

    /**
     * Set newsletter
     *
     * @param integer $newsletter
     *
     * @return Client
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return int
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Client
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set sujet
     *
     * @param string $sujet
     *
     * @return Client
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }
}
