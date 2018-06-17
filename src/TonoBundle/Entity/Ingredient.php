<?php

namespace TonoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Ingredient
 *
 * @ORM\Table(name="ingredient")
 * @ORM\Entity(repositoryClass="TonoBundle\Repository\IngredientRepository")
 */
class Ingredient
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
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, unique=false, nullable=true)
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="isvegan", type="boolean")
     */
    private $isvegan;

    /**
     * @var int
     *
     * @ORM\Column(name="defaultquantity", type="integer", nullable=true)
     */
    private $defaultquantity;

    /**
     * @var ArrayCollection Recette $recettes
     *
     * @ORM\ManyToMany(targetEntity="Recette", mappedBy="ingredients")
     */
    private $recettes;
    
    
    
    
    
    
    

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
     * @return Ingredient
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
     * Set type
     *
     * @param string $type
     *
     * @return Ingredient
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    
    
    
    /**
     * Set isvegan
     *
     * @param boolean $isvegan
     *
     * @return Ingredient
     */
    public function setIsvegan($isvegan)
    {
        $this->isvegan = $isvegan;

        return $this;
    }

    /**
     * Get isvegan
     *
     * @return bool
     */
    public function getIsvegan()
    {
        return $this->isvegan;
    }

    /**
     * Set defaultquantity
     *
     * @param integer $defaultquantity
     *
     * @return Ingredient
     */
    public function setDefaultquantity($defaultquantity)
    {
        $this->defaultquantity = $defaultquantity;

        return $this;
    }

    /**
     * Get defaultquantity
     *
     * @return int
     */
    public function getDefaultquantity()
    {
        return $this->defaultquantity;
    }
    
    /**
     * Get ArrayCollection
     *
     * @return ArrayCollection $recettes
     */
    public function getRecettes()
    {
        return $this->recettes;
    }
 
    public function __construct()
    {
        $this->recettes = new ArrayCollection();
    }
}

