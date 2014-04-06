<?php

namespace Sf\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Request
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Request
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
     * @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\Foyer")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $foyer;

    /**
     * @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accepted", type="boolean")
     */
    private $accepted;


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
     * Set user
     *
     * @param Sf\UserBundle\Entity\User $user
     */
    public function setUser(\Sf\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Sf\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set accepted
     *
     * @param boolean $accepted
     * @return Request
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;

        return $this;
    }

    /**
     * Get accepted
     *
     * @return boolean 
     */
    public function getAccepted()
    {
        return $this->accepted;
    }
}
