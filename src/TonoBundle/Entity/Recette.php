<?php

namespace TonoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
 use Doctrine\Common\Collections\ArrayCollection;
/**
 * Recette
 *
 * @ORM\Table(name="recette")
 * @ORM\Entity(repositoryClass="TonoBundle\Repository\RecetteRepository")
 */
class Recette
{
    CONST LOURD_NAMES=array(
        0=>'Midi',
        1=>'Soir (vegetarien)',
        2=>'Adultes Only',
        3=>'Special (non selectionné automatiquement)'
    );
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
     * @var ArrayCollection Ingredient $ingredients
     *
     * Inverse Side
     *
     * @ORM\ManyToMany(targetEntity="Ingredient", inversedBy="recettes")
     */
    private $ingredients;
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="isvegan", type="boolean")
     */
    private $isVegan;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="lourd", type="integer")
     */
    private $lourd;
    
    /**
     * @var string
     *
     * @ORM\Column(name="indications", type="string", length=65535,nullable=true)
     */
    private $indications;
    
    /**
     * @var text
     *
     * @ORM\Column(name="season", type="text", nullable=true)
     */
    private $season;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="disabled", type="boolean")
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Recette
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
     * Set season
     *
     * @param string $season
     *
     * @return Recette
     */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return string
     */
    public function getSeason()
    {
        return $this->season;
    }
    
    
    /**
     * Set indications
     *
     * @param string $indications
     *
     * @return Recette
     */
    public function setIndications($indications)
    {
        $this->indications = $indications;

        return $this;
    }

    /**
     * Get indications
     *
     * @return string
     */
    public function getIndications()
    {
        return $this->indications;
    }
    
    /**
     * Set isVegan
     *
     * @param boolean $isVegan
     *
     * @return Recette
     */
    public function setIsVegan($isVegan)
    {
        $this->isVegan = $isVegan;

        return $this;
    }

    /**
     * Get isVegan
     *
     * @return boolean
     */
    public function getIsVegan()
    {
        return $this->isVegan;
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
    
    /**
     * Set lourd
     *
     * @param integer $lourd
     *
     * @return Recette
     */
    public function setLourd($lourd)
    {
        $this->lourd = $lourd;

        return $this;
    }

    /**
     * Get lourd
     *
     * @return integer
     */
    public function getLourd()
    {
        return $this->lourd;
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

