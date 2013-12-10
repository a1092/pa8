<?php

namespace Sf\InstitutionnelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SfInstitutionnelBundle:Default:index.html.twig', array());
    }
	
	public function teamAction()
    {
        return $this->render('SfInstitutionnelBundle:Default:team.html.twig', array());
    }
}
