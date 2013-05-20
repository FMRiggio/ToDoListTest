<?php
namespace ToDoList\ToDoListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use ToDoList\ToDoListBundle\Entity\Task,
	ToDoList\ToDoListBundle\Form\TaskType;

class IndexController extends BaseController
{

    /**
     * @Route("/", name="_index")
     * @Template()
     */
    public function indexAction()
    {
    	$user = $this->get('security.context')->getToken()->getUser();
		$repo = $this->getDoctrine()->getRepository('ToDoListBundle:Task');
		$taskList = $repo->getTaskListByUserId($user->getId());

        return $this->render('ToDoListBundle:Index:index.html.twig', array(
			'tasklist'  => $taskList,
		));
    }


    /**
     * @Route("/add-task", name="_add_task")
     * @Template()
     */
	public function addTaskAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$task = new Task();
		$form = $this->createForm(new TaskType(), $task);

		$request = $this->getRequest();
		if ($request->isMethod('POST')) {
			$form->bind($request);

	        if ($form->isValid()) {
				$task->setUserId($user);
				//$task->setUserId($user->getId());
				// Initial status => active
				$task->setStatus($task::$ACTIVE);

	        	$em = $this->getDoctrine()->getEntityManager();
				$em->persist($task);
				$em->flush();

				return $this->redirect($this->generateUrl('_index'));

			}
		}

        return $this->render('ToDoListBundle:Index:add-task.html.twig', array(
        	'form' => $form->createView(),
		));
	}

    /**
     * @Route("/edit-task/{taskId}", name="_edit_task")
     * @Template()
     */
	public function editTaskAction($taskId)
	{
		$viewParams = parent::editTask($taskId);
		if ($viewParams === TRUE) {
			return $this->redirect($this->generateUrl('_index'));
		}
 		return $this->render('ToDoListBundle:Index:edit-task.html.twig', $viewParams);
	}

    /**
     * @Route("/delete-task/{taskId}", name="_delete_task")
     * @Template()
     */
	public function deleteTaskAction($taskId)
	{
		parent::deleteTask($taskId);
        return $this->redirect($this->generateUrl('_index'));
	}

    /**
     * @Route("/task-complete/{taskId}", name="_complete_task")
     * @Template()
     */
	public function taskCompleteAction($taskId)
	{
		parent::taskComplete($taskId);
        return $this->redirect($this->generateUrl('_index'));
	}

    /**
     * @Route("/reactive-task/{taskId}", name="_reactive_task")
     * @Template()
     */
	public function reactiveTaskAction($taskId)
	{
		parent::reactiveTask($taskId);
		return $this->redirect($this->generateUrl('_index'));
	}
}
