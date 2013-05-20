<?php

namespace ToDoList\ToDoListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

use ToDoList\ToDoListBundle\Entity\User;
use ToDoList\ToDoListBundle\Form\RegistrationType;

/**
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * @Route("/", name="_user")
     * @Template()
     */
    public function indexAction()
    {
    	$this->redirect('/');
		return;
    }

    /**
     * @Route("/registration", name="_user_registration")
     * @Template()
     */
	public function registrationAction()
	{
		$user = new User();
		$form = $this->createForm(new RegistrationType(), $user);

		$request = $this->getRequest();
		if ($request->isMethod('POST')) {
			$form->bind($request);

	        if ($form->isValid()) {
				// TODO: next step is the activation by url inside the email
				$user->setIsActive(TRUE);
				$factory = $this->get('security.encoder_factory');
				$password = $factory->getEncoder($user)
									->encodePassword($user->getPassword(), $user->getSalt());
				$user->setPassword($password);
				$user->setFbUserId('');

				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($user);
				$em->flush();

				return $this->redirect($this->generateUrl('_user_registration_complete'));
	        }
    	}

		return $this->render('ToDoListBundle:User:registration.html.twig', array(
			'form' => $form->createView(),
		));
	}


    /**
     * @Route("/fb-registration", name="_user_fb_registration")
     * @Template()
     */
	public function fbRegistrationAction()
	{
		$request = $this->getRequest();
		$data = $request->request->all();
		$em   = $this->getDoctrine()->getEntityManager();
		$repo = $this->getDoctrine()->getRepository('ToDoListBundle:User');

		$user = NULL;
		if (isset($data['username'])) {
			$user = $repo->loadUserByUsername($data['username']);
		}
		if (!$user && isset($data['email'])) {
			$user = $repo->loadUserByUsername($data['email']);
		}

		if (!$user) {
			$user = new User();

			if (!isset($data['firstname'])) {
				throw new Exception("Error. Firstname missed.", 1);
			}
			$user->setFirstname($data['firstname']);
	
			if (!isset($data['lastname'])) {
				throw new Exception("Error. Lastname missed.", 1);
			}
			$user->setLastname($data['lastname']);
	
			if (!isset($data['username'])) {
				throw new Exception("Error. Username missed.", 1);
			}
			$user->setUsername($data['username']);

			if (!isset($data['email'])) {
				throw new Exception("Error. Email missed.", 1);
			}
			$user->setEmail($data['email']);

			if (!isset($data['fbUserId'])) {
				throw new Exception("Error. FB UserId missed.", 1);
			}
			$user->setFbUserId($data['fbUserId']);

			$user->setIsActive(TRUE);
			$factory = $this->get('security.encoder_factory');
			$password = $factory->getEncoder($user)
								->encodePassword(uniqid(), $user->getSalt());
			$user->setPassword($password);

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($user);
			$em->flush();

		}

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'secured_area', $user->getRoles());

		$this->container->get('security.context')->setToken($token);
        $session = $request->getSession();
        $session->set('_security_secured_area',  serialize($token));
 
        $url = $this->get('router')->generate('_index');
 
 		echo $url;
        return NULL;

	}



    /**
     * @Route("/registration-complete", name="_user_registration_complete")
     * @Template()
     */
	public function registrationCompleteAction()
	{
		return $this->render('ToDoListBundle:User:registration-complete.html.twig');
	}


    /**
     * @Route("/login", name="_user_login")
     * @Template()
     */
    public function loginAction()
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * @Route("/check-login", name="_user_check_login")
     */
    public function checkLoginAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="_user_logout")
     * @Template()
     */
    public function logoutAction()
    {
    }

}
