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

		$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SfUserBundle:User')->findOneByUsername($params->userName);
					   
		if(!$user) { return new JsonResponse(array('data' => "fail")); }
		
		$passwordDb = $user->getPassword();
					   
		$encoder_service = $this->get('security.encoder_factory');
		$encoder = $encoder_service->getEncoder($user);
		$encoded_pass = $encoder->encodePassword($params->password, $user->getSalt());
		
		
		if(($passwordDb == $encoded_pass)){
				$data = "success";
				return new JsonResponse(array('data' => $data, 'id' => $user->getId()));
			}
		
		$data = "fail";
		return new JsonResponse(array('data' => $data));
    }
}
