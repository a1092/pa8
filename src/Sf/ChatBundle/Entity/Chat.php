<?php

namespace Sf\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sf\ChatBundle\Entity\ChatRepository")
 */
class Chat
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
     * @var boolean
     *
     * @ORM\Column(name="private", type="boolean")
     */
    private $private;    

    /**
     * @ORM\ManyToMany(targetEntity="Sf\UserBundle\Entity\User", cascade={"persist"})
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="Sf\ChatBundle\Entity\Message", mappedBy="chat")
     */
    protected $messages;

    /**
     * @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\Foyer", inversedBy="chats")
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
     * Get private
     *
     * @return boolean
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set private
     *
     * @param boolean $private
     * @return Chat
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
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
     * @param Sf\ChatBundle\Entity\Message $message
     * @return Message
     */
    public function addMessage(\Sf\ChatBundle\Entity\Message $message)
    {
        $this->messages[] = $message;
        $message->setChat($this);
        return $this;
    }

    /**
     * @param Sf\ChatBundle\Entity\Message $message
     */
    public function removeMessage(\Sf\ChatBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
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
