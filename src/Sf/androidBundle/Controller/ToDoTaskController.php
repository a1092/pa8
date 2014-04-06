<?php

namespace Sf\androidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sf\ContactBundle\Entity\Contact;
use Sf\UserBundle\Entity\Foyer;
use Sf\ContactBundle\Form\ContactType;
use Sf\ContactBundle\Form\SearchContactType;

use Sf\ShoppingBundle\Entity\ShoppingList;
use Sf\ShoppingBundle\Entity\Article;
use Sf\TodoBundle\Entity\Task;
use Sf\TodoBundle\Entity\TaskRepository;

class ToDoTaskController extends Controller
{
    public function getToDoTasksAction() // OK
    {
		$content = $this->get("request")->getContent();
        $params = json_decode($content);
        $id = $params->id;
		
		$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SfUserBundle:User')->findOneById($id);
		
        $foyers = $user->getFoyers();
		
        $entities = $em->getRepository('SfTodoBundle:Task')->getPersonnalList($foyers[$user->getCurrentFoyer()], $user);
		if(!$entities) { return new JsonResponse(array('data' => "fail")); }
		
		foreach($entities as $task){
		
			$taskUsers = $task->getUsers();
			foreach($taskUsers as $taskUser){
				$taskUser = array('taskUserId' => $user->getId(),
				'taskUserName' => $user->getUsername(),
				);
				$finalTaskUsers[] = $taskUser;
				$taskUser = array();
			}
			
		    $tab = array('taskId' => $task->getId(),
			'taskName' => $task->getName(),
			'taskDescription' => $task->getDescription(),
			'taskDeadline' => $task->getDeadline(),
			'users' => $finalTaskUsers,
			);
			$finalTaskUsers = array();
			$taskUser = array();
			
		    $tabToDoTasks[] = $tab;
			$tab = array();
		}
		
		
		$entities = $em->getRepository('SfUserBundle:User')->getUserList($foyers[$user->getCurrentFoyer()]);
		foreach($entities as $user){
			$tab = array('userId' => $user->getId(),
			'userName' => $user->getUsername(),
			);
			
		    $tabUsers[] = $tab;
		    
           $tab = array();
		}
		
        return new JsonResponse(array('tabToDoTasks' => $tabToDoTasks,
		'tabUsers' => $tabUsers));
	}
}