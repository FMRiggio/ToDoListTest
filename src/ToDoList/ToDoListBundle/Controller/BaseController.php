<?php
namespace ToDoList\ToDoListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use ToDoList\ToDoListBundle\Entity\Task,
	ToDoList\ToDoListBundle\Form\TaskType;

class BaseController extends Controller
{
	protected function editTask($taskId)
	{
		$em   = $this->getDoctrine()->getEntityManager();
		$task = $this->getDoctrine()->getRepository('ToDoListBundle:Task')->find($taskId);

		if (!$task) {
			throw $this->createNotFoundException('Task with the id ' . $id . ' not found.');
		}
		$form = $this->createForm(new TaskType(), $task);

		$request = $this->getRequest();
		if ($request->isMethod('POST')) {
			$form->bind($request);
	        if ($form->isValid()) {
				$em->flush();
				// I manage the redirect in the action
				return TRUE;
			}
		}

        return array(
        	'form' => $form->createView(),
        	'task' => $task
		);
	}

	protected function deleteTask($taskId)
	{
		$em   = $this->getDoctrine()->getEntityManager();
		$task = $this->getDoctrine()->getRepository('ToDoListBundle:Task')->find($taskId);

		if (!$task) {
			throw $this->createNotFoundException('Task with the id ' . $id . ' not found.');
		}

		$em->remove($task);
		$em->flush();
		return TRUE;
	}

	protected function taskComplete($taskId)
	{
		$em   = $this->getDoctrine()->getEntityManager();
		$task = $this->getDoctrine()->getRepository('ToDoListBundle:Task')->find($taskId);

		if (!$task) {
			throw $this->createNotFoundException('Task with the id ' . $id . ' not found.');
		}

		$task->setStatus($task::$COMPLETED);
		$em->flush();
		return TRUE;
	}

	protected function reactiveTask($taskId)
	{
		$em   = $this->getDoctrine()->getEntityManager();
		$task = $this->getDoctrine()->getRepository('ToDoListBundle:Task')->find($taskId);

		if (!$task) {
			throw $this->createNotFoundException('Task with the id ' . $id . ' not found.');
		}

		$task->setStatus($task::$ACTIVE);
		$em->flush();
		return TRUE;
	}
}