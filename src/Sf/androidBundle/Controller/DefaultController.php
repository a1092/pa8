<?php

namespace Sf\androidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sf\UserBundle\Entity\User;
use FOS\UserBundle\Model\User as BaseUser;


class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SfandroidBundle:Default:index.html.twig', array('name' => $name));
    }
	
	public function connexionAction()
    {
		$content = $this->get("request")->getContent();
		$params = json_decode($content);

		$repository = $this->getDoctrine()
					   ->getManager()
					   ->getRepository('SfUserBundle:User');
		$listeUser = $repository->findAll();
		
		$passwordDb = '';
		
		foreach($listeUser as $user)
		{
			$passwordDb = $user->getPassword();
			$userNameDb = $user->getUserName();
			$password = $params->password;
			
			$encoder_service = $this->get('security.encoder_factory');
			$encoder = $encoder_service->getEncoder($user);
			$encoded_pass = $encoder->encodePassword($password, $user->getSalt());
			
			if(($passwordDb == $encoded_pass) && ($userNameDb == $params->userName)){
				$data = "success";
				return new JsonResponse(array('data' => $data));
			}
		}
		$data = "fail";
		return new JsonResponse(array('data' => $data));
    }
}
