<?php

namespace Sf\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Foyer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sf\UserBundle\Entity\FoyerRepository")
 */
class Foyer
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Sf\UserBundle\Entity\User", cascade={"persist"})
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="Sf\ContactBundle\Entity\Contact", mappedBy="foyer")
     */
    protected $contacts;

    /**
     * @ORM\OneToMany(targetEntity="Sf\ShoppingBundle\Entity\ShoppingList", mappedBy="foyer", cascade={"persist"})
     */
    protected $shoppingLists;

    /**
     * @ORM\OneToMany(targetEntity="Sf\TodoBundle\Entity\Task", mappedBy="foyer")
     */
    protected $tasks;

    /**
     * @ORM\OneToMany(targetEntity="Sf\LoanBundle\Entity\Loan", mappedBy="foyer")
     */
    protected $loans;

    /**
     * @ORM\OneToMany(targetEntity="Sf\ChatBundle\Entity\Chat", mappedBy="foyer")
     */
    protected $chats;


    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return User
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
     * Add user
     *
     * @param Sf\UserBundle\Entity\User $user
     */
    public function addUser(\Sf\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;
    }

    /**
     * Remove users
     *
     * @param Sf\UserBundle\Entity\User $users
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
     * @param Sf\ContactBundle\Entity\Contact $contact
     * @return Contact
     */
    public function addContact(\Sf\ContactBundle\Entity\Contact $contact)
    {
        $this->contacts[] = $contact;
        $contact->setFoyer($this);
        return $this;
    }

    /**
     * @param Sf\ContactBundle\Entity\Contact $contact
     */
    public function removeContact(\Sf\ContactBundle\Entity\Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param Sf\ShoppingBundle\Entity\ShoppingList $shoppingList
     * @return ShoppingList
     */
    public function addShoppingList(\Sf\ShoppingBundle\Entity\ShoppingList $shoppingList)
    {
        $this->shoppingLists[] = $shoppingList;
        $shoppingList->setFoyer($this);
        return $this;
    }

    /**
     * @param Sf\ShoppingBundle\Entity\ShoppingList $shoppingList
     */
    public function removeShoppingList(\Sf\ShoppingBundle\Entity\ShoppingList $shoppingList)
    {
        $this->shoppingLists->removeElement($shoppingList);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getShoppingLists()
    {
        return $this->shoppingLists;
    }

    /**
     * @param Sf\TodoBundle\Entity\Task $task
     * @return Task
     */
    public function addTask(\Sf\TodoBundle\Entity\Task $task)
    {
        $this->tasks[] = $task;
        $task->setFoyer($this);
        return $this;
    }

    /**
     * @param Sf\TodoBundle\Entity\Task $task
     */
    public function removeTask(\Sf\TodoBundle\Entity\Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param Sf\LoanBundle\Entity\Loan $loan
     * @return Loan
     */
    public function addLoan(\Sf\LoanBundle\Entity\Loan $loan)
    {
        $this->loans[] = $loan;
        $loan->setFoyer($this);
        return $this;
    }

    /**
     * @param Sf\LoanBundle\Entity\Loan $loan
     */
    public function removeLoan(\Sf\LoanBundle\Entity\Loan $loan)
    {
        $this->loans->removeElement($loan);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getLoans()
    {
        return $this->loans;
    }

    /**
     * @param Sf\ChatBundle\Entity\Chat $chat
     * @return Chat
     */
    public function addChat(\Sf\ChatBundle\Entity\Chat $chat)
    {
        $this->chats[] = $chat;
        $chat->setFoyer($this);
        return $this;
    }

    /**
     * @param Sf\ChatBundle\Entity\Chat $chat
     */
    public function removeChat(\Sf\ChatBundle\Entity\Chat $chat)
    {
        $this->chats->removeElement($chat);
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChats()
    {
        return $this->chats;
    }
}
