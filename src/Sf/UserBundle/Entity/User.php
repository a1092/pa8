<?php

namespace Sf\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="sf_user")
 * @ORM\Entity(repositoryClass="Sf\UserBundle\Entity\UserRepository")
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

    /**
     * @ORM\Column(name="current_foyer", type="integer")
     */
    protected $current_foyer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registration_date", type="datetime")
     */
    protected $registrationDate;

    /**
     * @ORM\Column(name="number_of_connections", type="integer")
     */
    protected $numberOfConnections;

    /**
     * @ORM\Column(name="color", type="string", length=7)
     */
     protected $color;

     /**
     * @ORM\OneToMany(targetEntity="Sf\LoanBundle\Entity\Loan", mappedBy="borrower", cascade={"persist"})
     */
    protected $borrowedThings;

    /**
     * @ORM\OneToMany(targetEntity="Sf\LoanBundle\Entity\Loan", mappedBy="lender", cascade={"persist"})
     */
    protected $lentThings;

    /**
     * @ORM\ManyToMany(targetEntity="Sf\ChatBundle\Entity\Message", cascade={"persist"})
     */
    protected $notSeenMessages;

	 
	public function __construct()
    {
        parent::__construct();
        $this->foyer = new \Doctrine\Common\Collections\ArrayCollection();
        $current_foyer = 0;
        $numberOfConnections = 0;
        $registrationDate = new \DateTime('now');
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

        $foyers = $this->foyers;
        $count = 0;
        foreach($foyers as $f) {
            $count++;
        }
        $this->current_foyer = $count-1;
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

    /**
     * @param integer $foyer
     */
    public function setCurrentFoyer($foyer)
    {
        $this->current_foyer = $foyer;
    }

    /**
     * @return integer
     */
    public function getCurrentFoyer()
    {
        return $this->current_foyer;
    }

    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     * @return User
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime 
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @param integer $numberOfConnections
     */
    public function setNumberOfConnections($numberOfConnections)
    {
        $this->numberOfConnections = $numberOfConnections;
    }

    /**
     * @return integer
     */
    public function getNumberOfConnections()
    {
        return $this->numberOfConnections;
    }

    public function increaseNumberOfConnections()
    {
        $this->numberOfConnections++;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return User
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }
    
    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param Sf\LoanBundle\Entity\Loan $borrowedThing
     * @return Loan
     */
    public function addBorrowedThing(\Sf\LoanBundle\Entity\Loan $borrowedThing)
    {
        $this->borrowedThings[] = $borrowedThing;
        $borrowedThing->setBorrower($this);
        return $this;
    }

    /**
     * @param Sf\LoanBundle\Entity\Loan $borrowedThing
     */
    public function removeBorrowedThing(\Sf\LoanBundle\Entity\Loan $borrowedThing)
    {
        $this->borrowedThings->removeElement($borrowedThing);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getBorrowedThings()
    {
        return $this->borrowedThings;
    }

    /**
     * @param Sf\LoanBundle\Entity\Loan $lentThing
     * @return Loan
     */
    public function addLentThing(\Sf\LoanBundle\Entity\Loan $lentThing)
    {
        $this->lentThings[] = $lentThing;
        $lentThing->setLender($this);
        return $this;
    }

    /**
     * @param Sf\LoanBundle\Entity\Loan $lentThing
     */
    public function removeLentThing(\Sf\LoanBundle\Entity\Loan $lentThing)
    {
        $this->lentThings->removeElement($lentThing);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getLentThings()
    {
        return $this->lentThings;
    }

    /**
     * Add notSeenMessage
     *
     * @param Sf\ChatBundle\Entity\Message $notSeenMessage
     */
    public function addNotSeenMessage(\Sf\ChatBundle\Entity\Message $notSeenMessage)
    {
        $this->notSeenMessages[] = $notSeenMessage;
    }

    /**
     * Remove notSeenMessage
     *
     * @param Sf\ChatBundle\Entity\Message $notSeenMessage
     */
    public function removeNotSeenMessage(\Sf\ChatBundle\Entity\Message $notSeenMessage)
    {
        $this->notSeenMessages->removeElement($notSeenMessage);
    }

    /**
     * Get notSeenMessages
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getNotSeenMessages()
    {
        return $this->notSeenMessages;
    }
	
	public function __toString()
    {
        return $this->getUsername();
    }
}
