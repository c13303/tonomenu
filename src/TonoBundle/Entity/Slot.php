<?php

namespace TonoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slot
 *
 * @ORM\Table(name="slot")
 * @ORM\Entity(repositoryClass="TonoBundle\Repository\SlotRepository")
 */
class Slot {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="lourdeur", type="integer", nullable=true)
     */
    private $lourdeur;
    

    

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Slot
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set lourdeur
     *
     * @param integer $lourdeur
     *
     * @return Slot
     */
    public function setLourdeur($lourdeur) {
        $this->lourdeur = $lourdeur;

        return $this;
    }

    /**
     * Get lourdeur
     *
     * @return bool
     */
    public function getLourdeur() {
        return $this->lourdeur;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Slot
     */
    public function setPosition($position) {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return bool
     */
    public function getPosition() {
        return $this->position;
    }

}
