<?php

namespace Sf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sf\UserBundle\Entity\User;
use Sf\UserBundle\Entity\UserRepository;
use FOS\UserBundle\Model\User as BaseUser;


class TestController extends Controller
{
	public function testAction()
    {
		$em = $this->getDoctrine()->getManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		$foyers = $user->getFoyers();

		$entities = $em->getRepository('SfUserBundle:User')->getUserList($foyers[$user->getCurrentFoyer()]);
		$entitiesLoan = $em->getRepository('SfLoanBundle:Loan')->getPersonnalList($foyers[$user->getCurrentFoyer()], $user);
		
		return $this->render('FOSUserBundle::test.html.twig', array(
                'entities' => $entities,
                'entitiesLoan' => $entitiesLoan,
        ));
    }
}