<?php

namespace Sf\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sf\DashboardBundle\Entity\PostRepository")
 */
class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


	 /**
     * @var string
     *
     * @ORM\Column(name="type", type="text", nullable=true)
     */
    private $type;

	 /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;
	

	
	/**
     *  @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\User", cascade={"persist"})
     */
    private $createdBy;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="modificationDate", type="datetime", nullable=true)
     */
    private $modificationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime", nullable=true)
     */
    private $creationDate;
	
	
	/**
     * @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\Foyer", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $foyer;
	
	
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
     * Constructor
     */
    public function __construct()
    {
        
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Post
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
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set modificationDate
     *
     * @param \DateTime $modificationDate
     * @return Post
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Post
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
     * Set createdBy
     *
     * @param \Sf\UserBundle\Entity\User $createdBy
     * @return Post
     */
    public function setCreatedBy(\Sf\UserBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Sf\UserBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set foyer
     *
     * @param \Sf\UserBundle\Entity\Foyer $foyer
     * @return Post
     */
    public function setFoyer(\Sf\UserBundle\Entity\Foyer $foyer)
    {
        $this->foyer = $foyer;

        return $this;
    }

    /**
     * Get foyer
     *
     * @return \Sf\UserBundle\Entity\Foyer 
     */
    public function getFoyer()
    {
        return $this->foyer;
    }
}
