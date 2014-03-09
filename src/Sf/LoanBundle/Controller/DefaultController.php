<?php

namespace Sf\Bundle\LoanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SfLoanBundle:Default:index.html.twig', array('name' => $name));
    }
}
