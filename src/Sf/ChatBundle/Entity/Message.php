<?php

namespace Sf\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sf\ChatBundle\Entity\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="sentBy", type="integer")
     */
    private $sentBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sentDate", type="datetime")
     */
    private $sentDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="notSeen", type="integer")
     */
    private $notSeen;

    /**
     * @ORM\ManyToMany(targetEntity="Sf\UserBundle\Entity\User", cascade={"persist"})
     */
    protected $users;

    /**
     * @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\Foyer", inversedBy="messages")
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
     * Set content
     *
     * @param string $content
     * @return Message
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
     * Set sentBy
     *
     * @param integer $sentBy
     * @return Message
     */
    public function setSentBy($sentBy)
    {
        $this->sentBy = $sentBy;

        return $this;
    }

    /**
     * Get sentBy
     *
     * @return integer 
     */
    public function getSentBy()
    {
        return $this->sentBy;
    }

    /**
     * Set sentDate
     *
     * @param \DateTime $sentDate
     * @return Message
     */
    public function setSentDate($sentDate)
    {
        $this->sentDate = $sentDate;

        return $this;
    }

    /**
     * Get sentDate
     *
     * @return \DateTime 
     */
    public function getSentDate()
    {
        return $this->sentDate;
    }

    /**
     * Set notSeen
     *
     * @param integer $notSeen
     * @return Message
     */
    public function setNotSeen($notSeen)
    {
        $this->notSeen = $notSeen;

        return $this;
    }

    /**
     * Get notSeen
     *
     * @return integer 
     */
    public function getNotSeen()
    {
        return $this->notSeen;
    }

    /**
     * Add user
     *
     * @param Sf\UserBundle\Entity\User $user
     */
    public function addUser(\Sf\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;
    }

    /**
     * Remove user
     *
     * @param Sf\UserBundle\Entity\User $user
     */
    public function removeUser(\Sf\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
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
