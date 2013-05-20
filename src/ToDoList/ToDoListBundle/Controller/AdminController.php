<?php
namespace ToDoList\ToDoListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use ToDoList\ToDoListBundle\Entity\Task,
	ToDoList\ToDoListBundle\Entity\TaskRepository,
	ToDoList\ToDoListBundle\Form\TaskType;

/**
 * @Route("/admin")
 */
class AdminController extends BaseController
{

    /**
     * @Route("/", name="_admin")
     * @Template()
     */
    public function indexAction()
    {
		$repo = $this->getDoctrine()->getRepository('ToDoListBundle:Task');
		$taskList = $repo->getAllTasks();

        return $this->render('ToDoListBundle:Admin:index.html.twig', array(
			'tasklist'  => $taskList,
		));
    }

    /**
     * @Route("/edit-task/{taskId}", name="_admin_edit_task")
     * @Template()
     */
	public function editTaskAction($taskId)
	{
		$viewParams = parent::editTask($taskId);
		if ($viewParams === TRUE) {
			return $this->redirect($this->generateUrl('_admin'));
		}
 		return $this->render('ToDoListBundle:Admin:edit-task.html.twig', $viewParams);
	}

    /**
     * @Route("/delete-task/{taskId}", name="_admin_delete_task")
     * @Template()
     */
	public function deleteTaskAction($taskId)
	{
		parent::deleteTask($taskId);
        return $this->redirect($this->generateUrl('_admin'));
	}

    /**
     * @Route("/task-complete/{taskId}", name="_admin_complete_task")
     * @Template()
     */
	public function taskCompleteAction($taskId)
	{
		parent::taskComplete($taskId);
        return $this->redirect($this->generateUrl('_admin'));
	}

    /**
     * @Route("/reactive-task/{taskId}", name="_admin_reactive_task")
     * @Template()
     */
	public function reactiveTaskAction($taskId)
	{
		parent::reactiveTask($taskId);
		return $this->redirect($this->generateUrl('_admin'));
	}

}
