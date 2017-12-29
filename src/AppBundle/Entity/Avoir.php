<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avoir
 *
 * @ORM\Table(name="avoir")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvoirRepository")
 */
class Avoir
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
