<?php

namespace Sf\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sf\DashboardBundle\Entity\Post;
use Sf\DashboardBundle\Entity\PostRepository;
use Sf\DashboardBundle\Entity\Answer;
use Sf\DashboardBundle\Entity\AnswerRepository;
use Sf\DashboardBundle\Entity\AnswerUser;
use Sf\DashboardBundle\Entity\AnswerUserRepository;



class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SfDashboardBundle:Default:index.html.twig', array('name' => "hello"));
    }
	
	public function publishAction(Request $request) {
		
		$em = $this->getDoctrine()->getManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$post = new Post();
		
		$post->setType($request->request->get('publish-type'));
		$post->setContent($request->request->get('content'));
		$post->setCreatedBy($user);
		$post->setCreationDate(new \DateTime());
		
		
		if($post->getType() == "survey") {
		
			$surveycount = $request->request->get('survey-count');
		
			
			for($i=1; $i < $surveycount; $i++) {
				
				$answer = new Answer();
				$answer->setPost($post);
				$answer->setAnswer($request->request->get('survey-answer'.$i));
				
				if($answer->getAnswer() != "") {
					$em->persist($answer);
				}
			}
		}
		
		$foyers = $user->getFoyers();
		$post->setFoyer($foyers[$user->getCurrentFoyer()]);
		
		$em->persist($post);
		$em->flush();
		
		return new Response();
	}
	
	
	public function loadAction(Request $request) {
		
		$em = $this->getDoctrine()->getManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		$foyers = $user->getFoyers();
		
		
		//$date = strtotime("+2hours", $request->request->get('date'));
		$date = $request->request->get('date');
		
		$date = new \DateTime(date("d-m-Y H:i:s", $date));
		
		
		$posts = $em
                     ->getRepository('SfDashboardBundle:Post')
                     ->load($date, $foyers[$user->getCurrentFoyer()]);
					 
					 
		
		$publication = array();
		$i = 1;
		foreach($posts as $post) {
			
			$answers = array();	
			
			$alreadyAnswer = false;
			if($post->getType() == "survey") {
				
				$surveys = $em
                     ->getRepository('SfDashboardBundle:Answer')
                     ->findBy(array('post' => $post));
				
							
				$j = 1;
				foreach($surveys as $survey) {
					
					$answersUsers_result = $em
						 ->getRepository('SfDashboardBundle:AnswerUser')
						 ->findBy(array('answer' => $survey));
					
					$k = 1;
					$answersUsers = array();
					foreach($answersUsers_result as $prout) {
					
						if($prout->getUser() == $user) {
							$alreadyAnswer = true;
						}
						
						$answersUsers[$k] = array(
							'username' => $prout->getUser()->getUsername()
							
						);
						
						$k++;
					}
					
					$answers[$j] = array(
						'id' => $survey->getId(),
						'text' => $survey->getAnswer(),
						'users' => $answersUsers,
					);
					
					$j++;
				}
			}
			
				$avatar = "";
			//else
			//	$this->container->get('templating.helper.assets')->getUrl('img/logo.png', null);
			//$avatar =  $this->get('templating.helper.assets')->getUrl('img/logo.png', $packageName = null)
			
			$publication[$i] = array(
			
				'id' => $post->getId(),
				'type' => $post->getType(),
				'content' => $post->getContent(),
				'publisher' => $post->getCreatedBy()->getUsername(),
				'avatar' => $avatar,
				'date' => $post->getCreationDate()->format('d-m-Y h:i'),
				'answers' => $answers,
				'already_answers' => $alreadyAnswer,
				
			);
			
			$i++;
		}
		
		
	
		$response = new Response(json_encode($publication));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
	
	
	public function answerAction($id) {
		
		$em = $this->getDoctrine()->getManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		

		$answer = $em
                     ->getRepository('SfDashboardBundle:Answer')
                     ->find($id);
		
		
		$useranswer = new AnswerUser();
		$useranswer->setUser($user);
		$useranswer->setAnswer($answer);
		
		$em->persist($useranswer);
		$em->flush();
		
		return $this->redirect($this->generateUrl('sf_dashboard_home', array()));

	}
}
