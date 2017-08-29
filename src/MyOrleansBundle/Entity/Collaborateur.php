<?php

namespace MyOrleansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Collaborateur
 *
 * @ORM\Table(name="collaborateur")
 * @ORM\Entity(repositoryClass="MyOrleansBundle\Repository\CollaborateurRepository")
 */
class Collaborateur
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
     * @ORM\Column(name="nom", type="string", length=45, nullable=true)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 45,
     *      minMessage = "Le prénom saisi est court.",
     *      maxMessage = "Le prénom saisi est long."
     * )
     * @ORM\Column(name="prenom", type="string", length=45)
     */
    private $prenom;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     * @ORM\Column(name="fonction", type="string", length=45)
     */
    private $fonction;

    /**
     * @var string
     * @Assert\Type(
     *     type="string",
     *     message="La saisie n'est pas correcte."
     * )
     * @Assert\Length(
     *      max = 45000,
     *      maxMessage = "La bio saisie est trop longue."
     * )
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    private $bio;

    /**
     * @var string
     * @Assert\Url(
     *     message = "L'URL saisie n'est pas correcte",
     *     protocols = {"http", "https", "ftp"}
     * )
     * @ORM\Column(name="lien_Facebook", type="string", length=45, nullable=true)
     */
    private $lienFacebook;
    /**
     * @var string
     * @Assert\Url(
     *     message = "L'URL saisie n'est pas correcte",
     *     protocols = {"http", "https", "ftp"}
     * )
     * @ORM\Column(name="lien_linkedin", type="string", length=45, nullable=true)
     */
    private $lienLinkedin;

    /**
     * @var string
     * @Assert\Email(
     *     message = "L'adresse mail que vous avez renseigné n'est pas valide",
     *     checkMX = true
     * )
     * @ORM\Column(name="email", type="string", length=45, nullable=true)
     */
    private $email;
    /**
     * @ORM\OneToOne(targetEntity="Media", inversedBy="collaborateur",cascade={"persist"})
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
     * @return int
     */
    public function getTri()
    {
        return $this->tri;
    }

    /**
     * @param int $tri
     * @return Collaborateur
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
     * @return Collaborateur
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Collaborateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     *
     * @return Collaborateur
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;
        return $this;
    }

    /**
     * Get fonction
     *
     * @return string
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set bio
     *
     * @param string $bio
     *
     * @return Collaborateur
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
        return $this;
    }

    /**
     * Get bio
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set lienFacebook
     *
     * @param string $lienFacebook
     *
     * @return Collaborateur
     */
    public function setLienFacebook($lienFacebook)
    {
        $this->lienFacebook = $lienFacebook;
        return $this;
    }

    /**
     * Get lienFacebook
     *
     * @return string
     */
    public function getLienFacebook()
    {
        return $this->lienFacebook;
    }

    /**
     * Set lienLinkedin
     *
     * @param string $lienLinkedin
     *
     * @return Collaborateur
     */
    public function setLienLinkedin($lienLinkedin)
    {
        $this->lienLinkedin = $lienLinkedin;
        return $this;
    }

    /**
     * Get lienLinkedin
     *
     * @return string
     */
    public function getLienLinkedin()
    {
        return $this->lienLinkedin;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Collaborateur
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
     * Set media
     *
     * @param \MyOrleansBundle\Entity\Media $media
     *
     * @return Collaborateur
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
