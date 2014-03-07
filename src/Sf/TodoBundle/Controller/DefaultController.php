<?php

namespace Sf\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SfTodoBundle:Default:index.html.twig', array('name' => $name));
    }
}
