<?php

namespace ToDoList\ToDoListBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('username',  'text');
    	$builder->add('firstname', 'text');
		$builder->add('lastname',  'text');
    	$builder->add('email',     'text');
        $builder->add('password',  'password');
    }

    public function getName()
    {
        return 'registration';
    }
}
