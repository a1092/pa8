<?php

namespace Sf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sf\UserBundle\Entity\User;
use FOS\UserBundle\Model\User as BaseUser;


class TestController extends Controller
{
	public function testAction()
    {
		$zebnejgb = "Célia";


		$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SfUserBundle:User')->findOneByUsername($zebnejgb);

        $bferjbgjs = $user;
					   
		// là faut mettre un truc pour s'il trouve rien dans la bdd
		if(!$user) { return $this->render('FOSUserBundle::index.html.twig'); }

		// if(!is_object($bferjbgjs)) { return $this->render('FOSUserBundle::pouet.html.twig'); }

		$name =  $user->getFirstName();
		$pouet =  $user->getNumberOfConnections();
					   
		$passwordDb = $user->getPassword();
					   
		$encoder_service = $this->get('security.encoder_factory');
		$encoder = $encoder_service->getEncoder($user);
		$encoded_pass = $encoder->encodePassword("Celia", $user->getSalt());
		
		
		if(($passwordDb == $encoded_pass) /*&& ($userNameDb == $params->userName)*/){
				$data = "success";
				// T'auras besoin de l'id du user pour les autres controlleurs
				return $this->render('FOSUserBundle::bienvenue.html.twig');
			}
		
		$data = "fail";
		return $this->render('FOSUserBundle::index.html.twig');
    }
}