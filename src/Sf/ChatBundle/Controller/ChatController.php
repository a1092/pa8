<?php
namespace Sf\ChatBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sf\ChatBundle\Entity\Message;
use Sf\ChatBundle\Entity\MessageRepository;
use Sf\ChatBundle\Entity\Chat;
use Sf\ChatBundle\Entity\ChatRepository;
use Sf\ChatBundle\Form\MessageType;

/**
 * Message controller.
 *
 */
class ChatController extends Controller
{

    /**
     * Lists 20 Messages entities.
     *
     */
    public function loadAction()
    {
		
		$em = $this->getDoctrine()->getManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$chat = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Chat')
                     ->getOpenChat($user);
					// ->findBy(array("users" => $user, "open" => true));
		
		$chatters = array();
		$i = 1;
		foreach($chat as $c) {
			
			$users = array();
			
			foreach($c->getUsers() as $u) {
				if($u != $user) 
					$users[] = $u;
			}
			
			
			$msg_result = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Message')
					 ->findBy(array("chat" => $c));
			
			$j = 1;
			$messages = array();
			foreach($msg_result as $m) {
				$messages[$j] = array(
					"chatid" => $m->getChat()->getId(),
					"chatters_name" =>  implode(", ", $users),
					"sender" => $m->getSentBy()->getUsername(),
					"message" => $m->getContent(),
					"date" => $m->getSentDate(),
				);
				$j++;
			}
			
			
			$chatters[$i] = array(
				"chatid" => $c->getId(),
				'chatters_name' => implode(", ", $users),
				'messages' => $messages
			);
			
			$i++;
		}
		
		
		$response = new Response(json_encode(array('chatters' => $chatters)));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
    }
	
	
	public function createAction(Request $request)
    {
		
		$em = $this->getDoctrine()->getManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		$chatters = explode(",", $request->request->get('chatters'));
		$chatters_name = array();
		
		$chat = new Chat;
		$chat->setPrivate(true);
		
		if(!empty($chatters)) {
			foreach($chatters as $chatterid) {
			
				 $chatter = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('SfUserBundle:User')
						 ->find($chatterid);
						 
				$chat->addUser($chatter);
				$chatters_name[] = $chatter->getUsername();
			}
			
			$chat->addUser($user);
		}
		
		$foyers = $user->getFoyers();
		$chat->setFoyer($foyers[0]);
		$em->persist($chat);
		$em->flush();
		
		
		$response = new Response(json_encode(array(
			'chatid' => $chat->getId(),
			'chatters' => implode(", ", $chatters_name)
		)));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
    }

	public function sendAction(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$chatid = $request->request->get('chatid');
		$content = $request->request->get('message');
		
		$chat = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Chat')
                     ->find($chatid);

		if(!$chat) {
			$chat = new Chat;
			$chat->setPrivate(false);
			$foyers[$user->getCurrentFoyer()]->addChat($chat);
			foreach($foyers[$user->getCurrentFoyer()]->getUsers() as $reader) {
				$chat->addUser($reader);
			}
			
		}
		
		$chat->setOpen(true);
		$em->persist($chat);
		$em->flush();
		
		
		$message = new Message();
		
		$message->setContent($content);
		$message->setSentDate(new \DateTime());
		$message->setSentBy($user);
		$chat->addMessage($message);
		$notSeen = 0;
		
		$message->setNotSeen($notSeen);
						
		foreach($chat->getUsers() as $reader) {
			if ($reader != $user) {
				$reader->addNotSeenMessage($message);
				$message->setNotSeen($notSeen + 1);
				
				$notSeen = $notSeen + 1;
			}
		}

		$em->persist($message);
		$em->flush();
		
		return new Response();
	}
	
	public function receiveAction(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$date = ($request->request->get('date'));

		//echo date("d-m-Y H:i:s", $date);
		
//		$date = (\DateTime)  $date;
//		echo $date->format("d-m-Y H:i:s");
		$date = new \DateTime(date("d-m-Y H:i:s", $date));
		
		$result = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Message')
                     ->receiveMessages($date, $user);
		
		$messages = array();
		
		
		
			
	
		
		$i = 1;
		foreach($result as $m) {
		
			$users = array();
			
			foreach($m->getChat()->getUsers() as $u) {
				if($u != $user) 
					$users[] = $u;
			}
			
			$messages[$i] = array(
			
				"chatid" => $m->getChat()->getId(),
				"chatters_name" =>  implode(", ", $users),
				"sender" => $m->getSentBy()->getUsername(),
				"message" => $m->getContent(),
				"date" => $m->getSentDate(),
				
			);
		
		}
		
		$response = new Response(json_encode(array('messages' => $messages)));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
	
	
	
	public function archiveAction(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$chatid = $request->request->get('chatid');
		
		$chat = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Chat')
                     ->find($chatid);
		
		if($chat) {
			$chat->setOpen(false);
			$em->persist($chat);
			$em->flush();
		}
		return new Response();
	}
}