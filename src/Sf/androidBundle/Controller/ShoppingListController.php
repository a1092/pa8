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


class ShoppingListController extends Controller
{
	
    public function getShoppingListsAction() // OK
    {
		$content = $this->get("request")->getContent();
        $params = json_decode($content);
        $id = $params->id;
		
		$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SfUserBundle:User')->findOneById($id);
		
        $foyers = $user->getFoyers();

        $entities = $em->getRepository('SfShoppingBundle:ShoppingList')->findByFoyer($foyers[$user->getCurrentFoyer()]);
		if(!$entities) { return new JsonResponse(array('data' => "fail")); }
		
		foreach($entities as $shoppingList){
		
			$articles = $shoppingList->getArticles();
			foreach($articles as $singleArticle){
				$article = array('articleId' => $singleArticle->getId(),
				'articleName' => $singleArticle->getName(),
					'articleQuantity' => $singleArticle->getQuantity(),
				);
				$finalArticles[] = $article;
				$article = array();
			}
			
		    $tab = array('listId' => $shoppingList->getId(),
			'listName' => $shoppingList->getName(),
			'listDeadline' => $shoppingList->getDeadline(),
			'articles' => $finalArticles,
						
			);
			$finalArticles = array();
			$article = array();
			
		    $final[] = $tab;
			$tab = array();
		}
        return new JsonResponse(array('entities' => $final));
	}

    public function newShoppingListAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        //Dans ton JSON, il faut : ID user
        $userId = $params->userId;

        $listName = $params->listName;
        $listDeadline = $params->listDeadline;

        $shoppingList = new ShoppingList;
        $shoppingList->setName($listName);
		$shoppingList->setDeadline($listDeadline);
		

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('SfUserBundle:User')->findOneById($userId);
        $foyers = $user->getFoyers();

		$shoppingList->setCreationDate(new \DateTime());
		$shoppingList->setModificationDate(new \DateTime());
		$shoppingList->setCreatedBy($user->getId());
		$shoppingList->setPrivate(false);
		foreach ($foyers[$user->getCurrentFoyer()]->getUsers() as $u) {
			$shoppingList->addUser($u);
		}
		
        $foyers[$user->getCurrentFoyer()]->addShoppingList($entity);
		
		/*
			if($shoppingList->getDeadline() != null) {
			$task = new Task();
			$task->setName($shoppingList->getName());
			$task->setDeadline($shoppingList->getDeadline());
			$task->setCreatedBy($user->getId());
			$task->setCreationDate($shoppingList->getCreationDate());
			$task->setModificationDate($shoppingList->getModificationDate());
			$foyers[$user->getCurrentFoyer()]->addTask($task);
			$task->setVisible(false);
			foreach ($shoppingList->getUsers() as $u) {
				$task->addUser($u);
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($task);
			$em->flush();

			$shoppingList->setIdTask($task->getId());
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($shoppingList);
		$em->flush();
		*/
		
		$entities = $em->getRepository('SfShoppingBundle:ShoppingList')->findByFoyer($foyers[$user->getCurrentFoyer()]);
		if(!$entities) { return new JsonResponse(array('data' => "fail")); }
		
		foreach($entities as $shoppingList){
		
			$articles = $shoppingList->getArticles();
			foreach($articles as $singleArticle){
				$article = array('articleId' => $singleArticle->getId(),
				'articleName' => $singleArticle->getName(),
					'articleQuantity' => $singleArticle->getQuantity(),
				);
				$finalArticles[] = $article;
				$article = array();
			}
			
		    $tab = array('listId' => $shoppingList->getId(),
			'listName' => $shoppingList->getName(),
			'listDeadline' => $shoppingList->getDeadline(),
			'articles' => $finalArticles,
						
			);
			$finalArticles = array();
			$article = array();
			
		    $final[] = $tab;
			$tab = array();
		}
        return new JsonResponse(array('entities' => $final));
    }
	
	public function newShoppingItemAction() // OK
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        //Dans ton JSON, il faut : ID user
        $userId = $params->idUser;
		$shoppingListId = $params->idShoppingList;

        $shoppingItem = new Article;
        $shoppingItemName = $params->shoppingItemName;
        $shoppingItemQuantity = $params->shoppingItemQuantity;

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SfUserBundle:User')->findOneById($userId);
        $foyers = $user->getFoyers();
        $shoppingList = $em->getRepository('SfShoppingBundle:ShoppingList')->findOneBy(array('id' => $shoppingListId, 'foyer' => $foyers[$user->getCurrentFoyer()]));

		

		$shoppingList->setModificationDate(new \DateTime());
		$shoppingItem->setAddBy($user->getId());
		$shoppingItem->setName($shoppingItemName);
		$shoppingItem->setQuantity($shoppingItemQuantity);
		$shoppingList->addArticle($shoppingItem);

		$em = $this->getDoctrine()->getManager();
		$em->persist($shoppingList);
		$em->persist($shoppingItem);
		$em->flush();
		
		$entities = $em->getRepository('SfShoppingBundle:ShoppingList')->findByFoyer($foyers[$user->getCurrentFoyer()]);
		if(!$entities) { return new JsonResponse(array('data' => "fail")); }
		
		foreach($entities as $shoppingList){
		
			$articles = $shoppingList->getArticles();
			foreach($articles as $singleArticle){
				$article = array('articleId' => $singleArticle->getId(),
				'articleName' => $singleArticle->getName(),
					'articleQuantity' => $singleArticle->getQuantity(),
				);
				$finalArticles[] = $article;
				$article = array();
			}
			
		    $tab = array('listId' => $shoppingList->getId(),
			'listName' => $shoppingList->getName(),
			'listDeadline' => $shoppingList->getDeadline(),
			'articles' => $finalArticles,
						
			);
			$finalArticles = array();
			$article = array();
			
		    $final[] = $tab;
			$tab = array();
		}
        return new JsonResponse(array('entities' => $final));
    }

    public function editShoppingItemAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        $idUser = $params->idUser;
        $idShoppingItem = $params->idShoppingItem;
		$shoppingListId = $params->idShoppingList;

        $shoppingItemName = $params->shoppingItemName;
        $shoppingItemQuantity = $params->shoppingItemQuantity;

		$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SfUserBundle:User')->findOneById($idUser);
        $foyers = $user->getFoyers();
        $shoppingList = $em->getRepository('SfShoppingBundle:ShoppingList')->findOneBy(array('id' => $shoppingListId, 'foyer' => $foyers[$user->getCurrentFoyer()]));

		$shoppingItem = $em->getRepository('SfShoppingBundle:Article')->findOneById($idShoppingItem);
		
		$shoppingList->setModificationDate(new \DateTime());
		$shoppingItem->setName($shoppingItemName);
		$shoppingItem->setQuantity($shoppingItemQuantity);
		
		$em->persist($shoppingItem);
        $em->flush();
		
        $entities = $em->getRepository('SfShoppingBundle:ShoppingList')->findByFoyer($foyers[$user->getCurrentFoyer()]);
		if(!$entities) { return new JsonResponse(array('data' => "fail")); }
		
		foreach($entities as $shoppingList){
		
			$articles = $shoppingList->getArticles();
			foreach($articles as $singleArticle){
				$article = array('articleId' => $singleArticle->getId(),
				'articleName' => $singleArticle->getName(),
					'articleQuantity' => $singleArticle->getQuantity(),
				);
				$finalArticles[] = $article;
				$article = array();
			}
			
		    $tab = array('listId' => $shoppingList->getId(),
			'listName' => $shoppingList->getName(),
			'listDeadline' => $shoppingList->getDeadline(),
			'articles' => $finalArticles,
						
			);
			$finalArticles = array();
			$article = array();
			
		    $final[] = $tab;
			$tab = array();
		}
        return new JsonResponse(array('entities' => $final));
    }

    public function deleteShoppingItemAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        $idUser = $params->idUser;
        $idShoppingItem = $params->idShoppingItem;

		$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SfUserBundle:User')->findOneById($idUser);
        $foyers = $user->getFoyers();
		
        $entity = $em->getRepository('SfShoppingBundle:Article')->findOneBy(array('id' => $idShoppingItem));
        $em->remove($entity);
        $em->flush();

		
		
        $entities = $em->getRepository('SfShoppingBundle:ShoppingList')->findByFoyer($foyers[$user->getCurrentFoyer()]);
		if(!$entities) { return new JsonResponse(array('data' => "fail")); }
		
		foreach($entities as $shoppingList){
		
			$articles = $shoppingList->getArticles();
			foreach($articles as $singleArticle){
				$article = array('articleId' => $singleArticle->getId(),
				'articleName' => $singleArticle->getName(),
					'articleQuantity' => $singleArticle->getQuantity(),
				);
				$finalArticles[] = $article;
				$article = array();
			}
			
		    $tab = array('listId' => $shoppingList->getId(),
			'listName' => $shoppingList->getName(),
			'listDeadline' => $shoppingList->getDeadline(),
			'articles' => $finalArticles,
						
			);
			$finalArticles = array();
			$article = array();
			
		    $final[] = $tab;
			$tab = array();
		}
        return new JsonResponse(array('entities' => $final));
    }
}