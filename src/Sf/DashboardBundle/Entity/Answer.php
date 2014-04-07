<?php

namespace Sf\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sf\DashboardBundle\Entity\AnswerRepository")
 */
class Answer
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
     *  @ORM\ManyToOne(targetEntity="Sf\DashboardBundle\Entity\Post", cascade={"persist"})
     */
    private $post;
	
	/**
     * @var string
     *
     * @ORM\Column(name="answer", type="text", nullable=true)
     */
    private $answer;
	
	
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
     * @param string $answer
     * @return Answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set post
     *
     * @param \Sf\DashboardBundle\Entity\Post $post
     * @return Answer
     */
    public function setPost(\Sf\DashboardBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Sf\DashboardBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }
}
