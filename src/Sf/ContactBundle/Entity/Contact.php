<?php

namespace Sf\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Contact
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="home_phone_number", type="string", length=20, nullable=true)
     */
    private $homePhoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_phone_number", type="string", length=20, nullable=true)
     */
    private $mobilePhoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="other_phone_number", type="string", length=20, nullable=true)
     */
    private $otherPhoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="text", nullable=true)
     */
    private $remark;

    /**
     * @var integer
     *
     * @ORM\Column(name="add_by", type="integer")
     */
    private $addBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modification_date", type="datetime")
     */
    private $modificationDate;

    /**
     * @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\Foyer", inversedBy="contacts")
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
     * Set name
     *
     * @param string $name
     * @return Contact
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
     * Set email
     *
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Contact
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set homePhoneNumber
     *
     * @param string $homePhoneNumber
     * @return Contact
     */
    public function setHomePhoneNumber($homePhoneNumber)
    {
        $this->homePhoneNumber = $homePhoneNumber;

        return $this;
    }

    /**
     * Get homePhoneNumber
     *
     * @return string 
     */
    public function getHomePhoneNumber()
    {
        return $this->homePhoneNumber;
    }

    /**
     * Set mobilePhoneNumber
     *
     * @param string $mobilePhoneNumber
     * @return Contact
     */
    public function setMobilePhoneNumber($mobilePhoneNumber)
    {
        $this->mobilePhoneNumber = $mobilePhoneNumber;

        return $this;
    }

    /**
     * Get mobilePhoneNumber
     *
     * @return string 
     */
    public function getMobilePhoneNumber()
    {
        return $this->mobilePhoneNumber;
    }

    /**
     * Set otherPhoneNumber
     *
     * @param string $otherPhoneNumber
     * @return Contact
     */
    public function setOtherPhoneNumber($otherPhoneNumber)
    {
        $this->otherPhoneNumber = $otherPhoneNumber;

        return $this;
    }

    /**
     * Get otherPhoneNumber
     *
     * @return string 
     */
    public function getOtherPhoneNumber()
    {
        return $this->otherPhoneNumber;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Contact
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return Contact
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get remark
     *
     * @return string 
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set addBy
     *
     * @param integer $addBy
     * @return Contact
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Contact
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
     * @return Contact
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
