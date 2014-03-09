<?php

namespace Sf\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SfChatBundle:Default:index.html.twig', array('name' => $name));
    }
}
