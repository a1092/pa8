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
     * @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\User", cascade={"persist"})
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
     * @ORM\ManyToOne(targetEntity="Sf\ChatBundle\Entity\Chat", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chat;


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
     * Set chat
     *
     * @param Sf\ChatBundle\Entity\Chat $chat
     */
    public function setChat(\Sf\ChatBundle\Entity\Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get chat
     *
     * @return Sf\ChatBundle\Entity\Chat 
     */
    public function getChat()
    {
        return $this->chat;
    }


    /**
     * Set sentBy
     *
     * @param \Sf\UserBundle\Entity\User $sentBy
     * @return Message
     */
    public function setSentBy(\Sf\UserBundle\Entity\User $sentBy = null)
    {
        $this->sentBy = $sentBy;

        return $this;
    }

    /**
     * Get sentBy
     *
     * @return \Sf\UserBundle\Entity\User 
     */
    public function getSentBy()
    {
        return $this->sentBy;
    }
}
