<?php
namespace Sf\ChatBundle\Controller;

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
class MessageController extends Controller
{

    /**
     * Lists 20 Messages entities.
     *
     */
    public function indexAction()
    {

        $form = $this->createForm(new MessageType());

        return $this->render('SfChatBundle:Message:index.html.twig', array(
            'form'   => $form->createView(),
            'page'       => 1,
        ));
        
    }

    /**
     * Lists 20 Messages entities.
     *
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();
        $em = $this->getDoctrine()->getManager();

        $chat = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Chat')
                     ->findOneBy(array('private' => false, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        $messages = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Message')
                     ->getMessages($chat, 10, 1);

        $notSeen = $user->getNotSeenMessages();

        for($j = count($notSeen) ; $j >=0 ; $j--) {
                    foreach ($messages as $m) {
                        if ($m == $notSeen[$j]) {
                            $user->removeNotSeenMessage($notSeen[$j]);
                            $m->setNotSeen($m->getNotSeen() - 1);

                            $em->persist($m);
                            $em->persist($user);
                            $em->flush();

                            $newMessages[] = $m;
                        }
                    }
                }

                $form = $this->createForm(new MessageType());

        if (isset($newMessages)) {
        foreach($messages as $message) {
            $in = false;
            foreach($newMessages as $m) {
                if ($m == $message) {
                    $in = true;
                }
            }
            if ($in == false) {
                 $oldMessages[] = $message;
            }
        }
    }

        if (!isset($oldMessages)) {
        $oldMessages = $messages;
    }
        
        if (isset($newMessages)) {
            return $this->render('SfChatBundle:Message:messages.html.twig', array(
            'oldMessages' => $oldMessages,
            'newMessages' => $newMessages,
            'page'       => 1,
            'form'   => $form->createView(),
        ));
        }

        return $this->render('SfChatBundle:Message:messages.html.twig', array(
            'oldMessages' => $oldMessages,
            'page'       => 1,
            'form'   => $form->createView(),
        ));
        
    }

    /**
     * Lists 20 Messages entities.
     *
     */
    public function previousAction($page)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();
        $em = $this->getDoctrine()->getManager();

        $chat = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Chat')
                     ->findOneBy(array('private' => false, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        $messages = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Message')
                     ->getMessages($chat, 10, $page+1);

        $notSeen = $user->getNotSeenMessages();

        for($j = count($notSeen) ; $j >=0 ; $j--) {
                    foreach ($messages as $m) {
                        if ($m == $notSeen[$j]) {
                            $user->removeNotSeenMessage($notSeen[$j]);
                            $m->setNotSeen($m->getNotSeen() - 1);

                            $em->persist($m);
                            $em->persist($user);
                            $em->flush();

                            $newMessages[] = $m;
                        }
                    }
                }

                $form = $this->createForm(new MessageType());

        if (isset($newMessages)) {
        foreach($messages as $message) {
            $in = false;
            foreach($newMessages as $m) {
                if ($m == $message) {
                    $in = true;
                }
            }
            if ($in == false) {
                 $oldMessages[] = $message;
            }
        }
    }

        if (!isset($oldMessages)) {
        $oldMessages = $messages;
    }

    if (isset($newMessages)) {
            return $this->render('SfChatBundle:Message:old.html.twig', array(
            'oldMessages' => $oldMessages,
            'newMessages' => $newMessages,
            'page'       => $page+1,
            'form'   => $form->createView(),
        ));
        }

        return $this->render('SfChatBundle:Message:old.html.twig', array(
            'oldMessages' => $oldMessages,
            'page'       => $page+1,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Lists 20 Messages entities.
     *
     */
    public function nextAction($page)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();
        $em = $this->getDoctrine()->getManager();

        $chat = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Chat')
                     ->findOneBy(array('private' => false, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        $messages = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Message')
                     ->getMessages($chat, 10, $page-1);

        $notSeen = $user->getNotSeenMessages();

        for($j = count($notSeen) ; $j >=0 ; $j--) {
                    foreach ($messages as $m) {
                        if ($m == $notSeen[$j]) {
                            $user->removeNotSeenMessage($notSeen[$j]);
                            $m->setNotSeen($m->getNotSeen() - 1);

                            $em->persist($m);
                            $em->persist($user);
                            $em->flush();

                            $newMessages[] = $m;
                        }
                    }
                }

                $form = $this->createForm(new MessageType());

        if (isset($newMessages)) {
        foreach($messages as $message) {
            $in = false;
            foreach($newMessages as $m) {
                if ($m == $message) {
                    $in = true;
                }
            }
            if ($in == false) {
                 $oldMessages[] = $message;
            }
        }
    }

        if (!isset($oldMessages)) {
        $oldMessages = $messages;
    }

    if (isset($newMessages)) {
        if($page-1 != 1) {
            return $this->render('SfChatBundle:Message:old.html.twig', array(
            'oldMessages' => $oldMessages,
            'newMessages' => $newMessages,
            'page'       => $page-1,
            'form'   => $form->createView(),
        ));
        }
        else {
            return $this->render('SfChatBundle:Message:index.html.twig', array(
            'oldMessages' => $oldMessages,
            'newMessages' => $newMessages,
            'page'       => $page-1,
            'form'   => $form->createView(),
            ));
        }
        }

        if($page-1 != 1) {
        return $this->render('SfChatBundle:Message:old.html.twig', array(
            'oldMessages' => $oldMessages,
            'page'       => $page-1,
            'form'   => $form->createView(),
        ));
        }
        else {
            return $this->render('SfChatBundle:Message:index.html.twig', array(
            'oldMessages' => $oldMessages,
            'page'       => $page-1,
            'form'   => $form->createView(),
            ));
        }
    }

	/**
     * Creates a new Message entity.
     *
     */
    public function newAction()
    {
        $entity = new Message;
        // On crée le formulaire grâce à l'ArticleType
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new MessageType, $entity);

        // On récupère la requête
        $request = $this->get('request');

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
          // On fait le lien Requête <-> Formulaire
          $form->bind($request);

            if ($form->isValid()) {
                $chat = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SfChatBundle:Chat')
                     ->findOneBy(array('private' => false, 'foyer' => $foyers[$user->getCurrentFoyer()]));

                if(!$chat) {
                    $chat = new Chat;
                    $chat->setPrivate(false);
                    $foyers[$user->getCurrentFoyer()]->addChat($chat);
                    foreach($foyers[$user->getCurrentFoyer()]->getUsers() as $reader) {
                        $chat->addUser($reader);
                    }
					
					
                    $em->persist($chat);
                    $em->flush();
                }

                $entity->setSentDate(new \DateTime());
                $entity->setSentBy($user->getId());
                $chat->addMessage($entity);
                $notSeen = 0;
				
				$entity->setNotSeen($notSeen);
								
                foreach($chat->getUsers() as $reader) {
                    if ($reader != $user) {
                        $reader->addNotSeenMessage($entity);
                        $entity->setNotSeen($notSeen + 1);
						
                        $notSeen = $notSeen + 1;
                    }
                }

                $em->persist($entity);
                $em->flush();

                $formM = $this->createForm(new MessageType());
                return $this->render('SfChatBundle:Message:index.html.twig', array('page' => 1, 'form'   => $formM->createView()));
            }
        }

        return $this->render('SfChatBundle:Message:index.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'page' => 1,
        ));
    }
}