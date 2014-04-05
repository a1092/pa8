<?php
namespace Sf\ChatBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Symfony\Component\Form\FormBuilderInterface;


use Sf\ChatBundle\Entity\Message;
use Sf\ChatBundle\Entity\Chat;
use Sf\ChatBundle\Form\MessageType;
use Sf\ChatBundle\Form\ConversationType;

/**
 * Message controller.
 *
 */
class MessagePrivateController extends Controller
{

    /**
     * Lists 20 Messages entities.
     *
     */
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        /* Première étape : l'utilisateur choisir les personnes avec qui il veut chatter */
        $conv = $this->createForm(new ConversationType($foyers[$user->getCurrentFoyer()]));

        $request = $this->getRequest();
        $requestInArray = $this->getRequest()->request->get('conversation');

        if ($request->getMethod() == 'POST') {
            $conv->bind($requestInArray);
            if ($conv->isValid())
            {
                $data = $requestInArray['users'];

                $em = $this->getDoctrine()->getManager();

                if($data != '')
                {

                    $em = $this->getDoctrine()->getManager();

                    /* Deuxième étape : on récupère les personnes avec qui l'utilisateur veut chatter
                     * S'il ne sait pas mis dans les personnes, on l'ajoute
                     */
                    $in = false;
                    foreach($data as $userChoosen) {
                        $u = $this->getDoctrine()->getManager()->getRepository('SfUserBundle:User')->findOneById($userChoosen);
                        $users[] = $u;
                        if($u == $user) {
                            $in = true;
                        }
                    }
                    if($in == false) {
                        $users[] = $user;
                    }

                    /* Troisième étape : on récupère tous les chats privés qui existent */
                    $chats = $this->getDoctrine()->getManager()->getRepository('SfChatBundle:Chat')->findBy(array('private' => true, 'foyer' => $foyers[$user->getCurrentFoyer()]));

                    // S'il n'y a pas encore de chat privé, on en créé un
                    if(!$chats) {
                        $chat = new Chat;
                        $chat->setPrivate(true);
                        $foyers[$user->getCurrentFoyer()]->addChat($chat);
                        $creator = false;

                        foreach($users as $reader) {
                            $chat->addUser($reader);
                            if($reader == $user) {
                                $creator = true;
                            }
                        }
                        if($creator == false) {
                            $chat->addUser($user);
                        }

                        $em->persist($chat);
                        $em->flush();
                    }
                    // S'il y a déjà des chats privés, on cherche si celui que l'utilisateur veut existe
                    else {
                        foreach ($chats as $c) {
                            foreach ($c->getUsers() as $uC) {
                                $bonChat = true;
                                $in = false;
                                foreach($users as $u) {
                                    if($u == $uC) {
                                        $in = true;
                                    }
                                }
                                if($in == false) {
                                    $bonChat = false;
                                }
                            }
                            if($bonChat == true && count($c->getUsers()) == count($users)) {
                                $chat = $c;
                            }
                    }
                    // Si celui que l'utilsateur veut n'existe pas, on le créé
                    if(!isset($chat) || !$chat) {
                        
                        $chat = new Chat;
                        $chat->setPrivate(true);
                        $foyers[$user->getCurrentFoyer()]->addChat($chat);
                        $creator = false;

                        foreach($users as $reader) {
                            $chat->addUser($reader);
                            if($reader == $user) {
                                $creator = true;
                            }
                        }
                        if($creator == false) {
                            $chat->addUser($user);
                        }

                        $em->persist($chat);
                        $em->flush();
                    }
                }

                $form = $this->createForm(new MessageType());

                return $this->render('SfChatBundle:Message:private.html.twig', array(
                'form'   => $form->createView(),
                'page'       => 1,
                'chat' => $chat->getId(),
                ));
            }
        }
    }

    return $this->render('SfChatBundle:Message:select.html.twig', array(
        'form'   => $conv->createView(),
        ));

}

    /**
     * Lists 20 Messages entities.
     *
     */
    public function showAction($chatId)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();
        $em = $this->getDoctrine()->getManager();

        $chat = $this->getDoctrine()
        ->getManager()
        ->getRepository('SfChatBundle:Chat')
        ->findOneById($chatId);

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
            'chat' => $chatId,
            'form'   => $form->createView(),
            ));
    }

    return $this->render('SfChatBundle:Message:messages.html.twig', array(
        'oldMessages' => $oldMessages,
        'page'       => 1,
        'chat' => $chatId,
        'form'   => $form->createView(),
        ));

    }

    /**
     * Lists 20 Messages entities.
     *
     */
    public function previousAction($page, $chatId)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();
        $em = $this->getDoctrine()->getManager();

        $chat = $this->getDoctrine()
        ->getManager()
        ->getRepository('SfChatBundle:Chat')
        ->findOneById($chatId);

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
        return $this->render('SfChatBundle:Message:oldprivate.html.twig', array(
            'oldMessages' => $oldMessages,
            'newMessages' => $newMessages,
            'page'       => $page+1,
            'chat' => $chatId,
            'form'   => $form->createView(),
            ));
    }

    return $this->render('SfChatBundle:Message:oldprivate.html.twig', array(
        'oldMessages' => $oldMessages,
        'page'       => $page+1,
        'chat' => $chatId,
        'form'   => $form->createView(),
        ));
    }

    /**
     * Lists 20 Messages entities.
     *
     */
    public function nextAction($page, $chatId)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();
        $em = $this->getDoctrine()->getManager();

        $chat = $this->getDoctrine()
        ->getManager()
        ->getRepository('SfChatBundle:Chat')
        ->findOneById($chatId);

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
            return $this->render('SfChatBundle:Message:oldprivate.html.twig', array(
                'oldMessages' => $oldMessages,
                'newMessages' => $newMessages,
                'page'       => $page-1,
                'chat' => $chatId,
                'form'   => $form->createView(),
                ));
        }
        else {
            return $this->render('SfChatBundle:Message:private.html.twig', array(
                'oldMessages' => $oldMessages,
                'newMessages' => $newMessages,
                'page'       => $page-1,
                'chat' => $chatId,
                'form'   => $form->createView(),
                ));
        }
    }

    if($page-1 != 1) {
        return $this->render('SfChatBundle:Message:oldprivate.html.twig', array(
            'oldMessages' => $oldMessages,
            'page'       => $page-1,
            'chat' => $chatId,
            'form'   => $form->createView(),
            ));
    }
    else {
        return $this->render('SfChatBundle:Message:private.html.twig', array(
            'oldMessages' => $oldMessages,
            'page'       => $page-1,
            'chat' => $chatId,
            'form'   => $form->createView(),
            ));
    }
}

	/**
     * Creates a new Message entity.
     *
     */
    public function newAction($chatId)
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
            $entity->setSentDate(new \DateTime());
            $entity->setSentBy($user->getId());
            $notSeen = 0;
            $chat = $this->getDoctrine()
            ->getManager()
            ->getRepository('SfChatBundle:Chat')
            ->findOneById($chatId);
			
			$entity->setNotSeen($notSeen);
			
            foreach($chat->getUsers() as $reader) {
                if ($reader != $user) {
                    $reader->addNotSeenMessage($entity);
                    $entity->setNotSeen($notSeen + 1);
                    $notSeen = $notSeen + 1;
                }
            }

            $chat->addMessage($entity);

            $em->persist($entity);
            $em->flush();

            $formM = $this->createForm(new MessageType());
            return $this->render('SfChatBundle:Message:private.html.twig', array('page' => 1, 'form'   => $formM->createView(), 'chat' => $chatId));
        }
    }

    return $this->render('SfChatBundle:Message:private.html.twig', array(
        'entity' => $entity,
        'form'   => $form->createView(),
        'page' => 1,
        'chat' => $chatId,
        ));
}
}