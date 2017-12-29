<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anonce
 *
 * @ORM\Table(name="anonce")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnonceRepository")
 */
class Anonce
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Anonce
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
     *
     * @ORM\ManyToOne(targetEntity="jeux")
     */
    private $jeux;
    function getJeux() {
        return $this->jeux;
    }

    function setJeux($jeux) {
        $this->jeux = $jeux;
    }

    /**
     *
     * @ORM\ManyToOne(targetEntity="jeux")
     */
    private $jeuxDemandee;
    function getJeuxDemandee() {
        return $this->jeuxDemandee;
    }

    function setJeuxDemandee($jeuxDemandee) {
        $this->jeuxDemandee = $jeuxDemandee;
    }

    /**
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     */
    private $utilisateur;
    function getUtilisateur() {
        return $this->utilisateur;
    }

    function setUtilisateur($utilisateur) {
        $this->utilisateur = $utilisateur;
    }
}
