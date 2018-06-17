<?php

namespace TonoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Meal
 *
 * @ORM\Table(name="meal")
 * @ORM\Entity(repositoryClass="TonoBundle\Repository\MealRepository")
 */
class Meal
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
     * @var int
     *
     * @ORM\OneToOne(targetEntity="TonoBundle\Entity\Slot", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $slot;

    

    /**
     * @var int
     *
     * @ORM\OneToOne(targetEntity="TonoBundle\Entity\Recette", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $recette;

    /**
     * @var boolean
     *
     * @ORM\Column(name="disabled", type="boolean",nullable=true)
     */
    private $disabled;
    
    

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
     * Set slot
     *
     * @param integer $slot
     *
     * @return Meal
     */
    public function setSlot($slot)
    {
        $this->slot = $slot;

        return $this;
    }

    /**
     * Get slot
     *
     * @return int
     */
    public function getSlot()
    {
        return $this->slot;
    }

    

    
    
    /**
     * Set recette
     *
     * @param integer $recette
     *
     * @return Meal
     */
    public function setRecette($recette)
    {
        $this->recette = $recette;

        return $this;
    }

    /**
     * Get recette
     *
     * @return int
     */
    public function getRecette()
    {
        return $this->recette;
    }
    
    /**
     * Set disabled
     *
     * @param boolean $disabled
     *
     * @return Recette
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return boolean
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

}

