<?php

namespace TonoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IngredientRecette
 *
 * @ORM\Table(name="ingredient_recette")
 * @ORM\Entity(repositoryClass="TonoBundle\Repository\IngredientRecetteRepository")
 */
class IngredientRecette
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
     * @var ArrayCollection Ingredient $ingredients
     *
     * Inverse Side
     *
     * @ORM\ManyToMany(targetEntity="Ingredient", inversedBy="recettes")
     */
    private $ingredients;
    

    /**
     * @var int
     *
     * @ORM\Column(name="qt", type="integer", nullable=true)
     */
    private $qt;


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
     * Set qt
     *
     * @param integer $qt
     *
     * @return IngredientRecette
     */
    public function setQt($qt)
    {
        $this->qt = $qt;

        return $this;
    }

    /**
     * Get qt
     *
     * @return int
     */
    public function getQt()
    {
        return $this->qt;
    }
    
    
    /**
     * Get ArrayCollection
     *
     * @return ArrayCollection $ingredients
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }
 
    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }
}

