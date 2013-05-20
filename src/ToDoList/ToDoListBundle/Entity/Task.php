<?php
namespace ToDoList\ToDoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * ToDoList\ToDoListBundle\Entity\Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="ToDoList\ToDoListBundle\Entity\TaskRepository")
 */
class Task
{
	public static $ACTIVE    = 'active';
	public static $COMPLETED = 'completed';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $task;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
	private $userId;

    public function getTask()
    {
        return $this->task;
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = ($status == self::$ACTIVE ? self::$ACTIVE : self::$COMPLETED);
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userId = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set dueDate
     *
     * @param \DateTime $dueDate
     * @return Task
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
    
        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime 
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Add userId
     *
     * @param \ToDoList\ToDoListBundle\Entity\User $userId
     * @return Task
     */
    public function addUserId(\ToDoList\ToDoListBundle\Entity\User $userId)
    {
        $this->userId[] = $userId;
    
        return $this;
    }

    /**
     * Remove userId
     *
     * @param \ToDoList\ToDoListBundle\Entity\User $userId
     */
    public function removeUserId(\ToDoList\ToDoListBundle\Entity\User $userId)
    {
        $this->userId->removeElement($userId);
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
    	$metadata->addPropertyConstraint('task', new Assert\NotBlank());
		$metadata->addPropertyConstraint('task', new Assert\MaxLength(array(
            'limit' => 100,
        )));
    }

}