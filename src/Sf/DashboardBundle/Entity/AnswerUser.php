<?php

namespace Sf\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnswerUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sf\DashboardBundle\Entity\AnswerUserRepository")
 */
class AnswerUser
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
     *  @ORM\ManyToOne(targetEntity="Sf\DashboardBundle\Entity\Answer", cascade={"persist"})
     */
    private $answer;

	/**
     *  @ORM\ManyToOne(targetEntity="Sf\UserBundle\Entity\User", cascade={"persist"})
     */
    private $user;

	
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
     * Set answer
     *
     * @param \Sf\DashboardBundle\Entity\Answer $answer
     * @return AnswerUser
     */
    public function setAnswer(\Sf\DashboardBundle\Entity\Answer $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \Sf\DashboardBundle\Entity\Answer 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set user
     *
     * @param \Sf\UserBundle\Entity\User $user
     * @return AnswerUser
     */
    public function setUser(\Sf\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sf\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
