<?php

namespace Sf\LoanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Loan
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Loan
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
     * @ORM\Column(name="item", type="string", length=255)
     */
    private $item;

    /**
     * @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\User", inversedBy="borrowedThings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $borrower;

    /**
     * @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\User", inversedBy="lentThings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lender;

    /**
     * @var integer
     *
     * @ORM\Column(name="createdBy", type="integer")
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modificationDate", type="datetime")
     */
    private $modificationDate;

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
     * Set item
     *
     * @param string $item
     * @return Loan
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return string 
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set borrower
     *
     * @param Sf\UserBundle\Entity\User $borrower
     */
    public function setBorrower(\Sf\UserBundle\Entity\User $borrower)
    {
        $this->borrower = $borrower;
    }

    /**
     * Get borrower
     *
     * @return Sf\UserBundle\Entity\User 
     */
    public function getBorrower()
    {
        return $this->borrower;
    }

    /**
     * Set lender
     *
     * @param Sf\UserBundle\Entity\User $lender
     */
    public function setLender(\Sf\UserBundle\Entity\User $lender)
    {
        $this->lender = $lender;
    }

    /**
     * Get lender
     *
     * @return Sf\UserBundle\Entity\User 
     */
    public function getLender()
    {
        return $this->lender;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return Loan
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
     * @return Loan
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
     * @return Loan
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
}
