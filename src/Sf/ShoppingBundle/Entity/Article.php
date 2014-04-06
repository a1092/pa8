<?php

namespace Sf\ShoppingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="string", length=20, nullable=true)
     */
    protected $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="addBy", type="integer", nullable=true)
     */
    protected $addBy;

    /**
     * @ORM\ManyToOne(targetEntity="Sf\ShoppingBundle\Entity\ShoppingList", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shoppingList;


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
     * Set name
     *
     * @param string $name
     * @return Article
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     * @return Article
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set addBy
     *
     * @param integer $addBy
     * @return Article
     */
    public function setAddBy($addBy)
    {
        $this->addBy = $addBy;

        return $this;
    }

    /**
     * Get addBy
     *
     * @return integer 
     */
    public function getAddBy()
    {
        return $this->addBy;
    }

    /**
     * Set shoppingList
     *
     * @param Sf\ShoppingBundle\Entity\ShoppingList $shoppingList
     */
    public function setShoppingList(\Sf\ShoppingBundle\Entity\ShoppingList $shoppingList)
    {
        $this->shoppingList = $shoppingList;
    }

    /**
     * Get shoppingList
     *
     * @return Sf\ShoppingBundle\Entity\ShoppingList 
     */
    public function getShoppingList()
    {
        return $this->shoppingList;
    }

}
