<?php

namespace Sf\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="sf_user")
 * @ORM\Entity
 */
class User extends BaseUser
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
	 * @ORM\Column(name="firstname", type="string", length=255)
     */
	 protected $firstname;
	 
	/**
	 * @ORM\Column(name="lastname", type="string", length=255)
     */
	 protected $lastname;

    /**
     * @ORM\Column(name="gender", type="string", length=255)
     */
     protected $gender;

    /**
     * @ORM\OneToOne(targetEntity="Sf\UserBundle\Entity\Image", cascade={"persist"})
     */
    protected $image;

    /**
     * @ORM\ManyToMany(targetEntity="Sf\UserBundle\Entity\Foyer", cascade={"persist"})
     */
    protected $foyers;

	 
	 public function __construct()
    {
        parent::__construct();
        $this->foyer = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;
		return $this;
	}
	
	/**
     * Get firstname
     *
     * @return string
     */
	public function getFirstname()
	{
		return $this->firstname;
	}
	
    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;
		return $this;
	}
	
	/**
     * Get lastname
     *
     * @return string 
     */
	public function getLastname()
	{
		return $this->lastname;
	}

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param Sf\UserBundle\Entity\Image $image
     */
    public function setImage(\Sf\UserBundle\Entity\Image $image = null)
    {
        $this->image = $image;
    }

    /**
     * @return Sf\UserBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add foyer
     *
     * @param Sf\UserBundle\Entity\Foyer $foyer
     */
    public function addFoyer(\Sf\UserBundle\Entity\Foyer $foyer)
    {
        $this->foyers[] = $foyer;
    }

    /**
     * Remove foyers
     *
     * @param Sf\UserBundle\Entity\Foyer $foyers
     */
    public function removeFoyer(\Sf\UserBundle\Entity\Foyer $foyer)
    {
        $this->foyers->removeElement($foyer);
    }

    /**
     * Get foyers
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getFoyers()
    {
        return $this->foyers;
    }
}
