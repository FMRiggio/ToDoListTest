<?php
namespace ToDoList\ToDoListBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
	public function getTaskListByUserId($userId)
	{
	    $q = $this->createQueryBuilder('t')
				  ->select('t')
				  ->where('t.userId = :userId')
				  ->setParameter('userId', $userId)
	    		  ->getQuery();
	
		try {
			$taskList = $q->getResult();
		} catch (NoResultException $e) {
			$message = 'Unable to find a tasklist.';
			throw new Exception($message, 0, $e);
		}
	    return $taskList;
	}

	public function getAllTasks()
	{
    	$q = $this->getEntityManager()
		       	  ->createQuery('
		            SELECT t, u FROM ToDoListBundle:Task t
		            JOIN t.userId u');

		try {
			$taskList = $q->getResult();
		} catch (NoResultException $e) {
			$message = 'Unable to find a tasklist.';
			throw new Exception($message, 0, $e);
		}
	    return $taskList;
	}
}