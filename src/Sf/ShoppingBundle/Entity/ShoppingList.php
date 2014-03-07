<?php

namespace Sf\ShoppingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShoppingList
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ShoppingList
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
     * @ORM\OneToMany(targetEntity="Sf\ShoppingBundle\Entity\Article", mappedBy="shoppingList", cascade={"persist"})
     */
    protected $articles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
    protected $deadline;

    /**
     * @var integer
     *
     * @ORM\Column(name="createdBy", type="integer")
     */
    protected $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    protected $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modificationDate", type="datetime")
     */
    protected $modificationDate;

    /**
     * @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\Foyer", inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $foyer;

    /**
     * @var integer
     *
     * @ORM\Column(name="idTask", type="integer", nullable=true)
     */
    protected $idTask;


    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dealine = new \DateTime('tomorrow');
    }


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
     * @return ShoppingList
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
     * @param Sf\ShoppingBundle\Entity\Article $article
     * @return Article
     */
    public function addArticle(\Sf\ShoppingBundle\Entity\Article $article)
    {
        $this->articles[] = $article;
        $article->setShoppingList($this);
        return $this;
    }

    /**
     * @param Sf\ShoppingBundle\Entity\Article $article
     */
    public function removeArticle(\Sf\ShoppingBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     * @return ShoppingList
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime 
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return ShoppingList
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return ShoppingList
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set modificationDate
     *
     * @param \DateTime $modificationDate
     * @return ShoppingList
     */
    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    /**
     * Get modificationDate
     *
     * @return \DateTime 
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    /**
     * Set foyer
     *
     * @param Sf\UserBundle\Entity\Foyer $foyer
     */
    public function setFoyer(\Sf\UserBundle\Entity\Foyer $foyer)
    {
        $this->foyer = $foyer;
    }

    /**
     * Get foyer
     *
     * @return Sf\UserBundle\Entity\Foyer 
     */
    public function getFoyer()
    {
        return $this->foyer;
    }

    /**
     * Set idTask
     *
     * @param integer $idTask
     * @return ShoppingList
     */
    public function setIdTask($idTask)
    {
        $this->idTask = $idTask;

        return $this;
    }

    /**
     * Get idTask
     *
     * @return integer 
     */
    public function getIdTask()
    {
        return $this->idTask;
    }
}
