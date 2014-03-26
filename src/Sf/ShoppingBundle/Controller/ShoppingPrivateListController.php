<?php

namespace Sf\ShoppingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sf\ShoppingBundle\Entity\ShoppingList;
use Sf\ShoppingBundle\Form\ShoppingPrivateListType;
use Sf\ShoppingBundle\Form\ArticleType;
use Sf\TodoBundle\Entity\Task;

/**
 * ShoppingList controller.
 *
 */
class ShoppingPrivateListController extends Controller
{

    /**
     * Lists all ShoppingList entities.
     *
     */
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $foyers = $user->getFoyers();

        $entities = $em->getRepository('SfShoppingBundle:ShoppingList')->getPersonnalList($foyers[$user->getCurrentFoyer()], $user, true);

        foreach ($entities as $entity) {
            $form[] = $this->createForm(new ArticleType())->createView();
        }

        if($entities) {
            return $this->render('SfShoppingBundle:ShoppingList:indexPrivate.html.twig', array(
                'entities' => $entities,
                'form'   => $form,
            ));
        }

        else {
            return $this->render('SfShoppingBundle:ShoppingList:indexPrivate.html.twig', array(
                'entities' => $entities,
            ));
        }
    }

    /**
     * Creates a new ShoppingList entity.
     *
     */
    public function newAction()
    {
        $entity = new ShoppingList;
        // On crée le formulaire grâce à l'ArticleType
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();
        $form = $this->createForm(new ShoppingPrivateListType($foyers[$user->getCurrentFoyer()]), $entity);

        // On récupère la requête
        $request = $this->get('request');

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
          // On fait le lien Requête <-> Formulaire
          $form->bind($request);

            if ($form->isValid()) {
                $entity->setCreationDate(new \DateTime());
                $entity->setModificationDate(new \DateTime());
                $entity->setCreatedBy($user->getId());
                $entity->setPrivate(true);

                $foyers[$user->getCurrentFoyer()]->addShoppingList($entity);

                if($entity->getDeadline() != null) {
                    $task = new Task();
                    $task->setName($entity->getName());
                    $task->setDeadline($entity->getDeadline());
                    $task->setCreatedBy($user->getId());
                    $task->setCreationDate($entity->getCreationDate());
                    $task->setModificationDate($entity->getModificationDate());
                    $foyers[$user->getCurrentFoyer()]->addTask($task);
                    $task->setVisible(false);
                    foreach ($entity->getUsers() as $u) {
                        $task->addUser($u);
                    }

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($task);
                    $em->flush();

                    $entity->setIdTask($task->getId());
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('shoppinglist_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('SfShoppingBundle:ShoppingList:newPrivate.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Edits an existing ShoppingList entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfShoppingBundle:ShoppingList')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ShoppingList entity.');
        }

        $form = $this->createForm(new ShoppingPrivateListType($foyers[$user->getCurrentFoyer()]), $entity);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $user = $this->container->get('security.context')->getToken()->getUser();
                $entity->setModificationDate(new \DateTime());

                if($entity->getDeadline() == null && $entity->getIdTask() != null) {
                    $task = $em->getRepository('SfTodoBundle:Task')->findOneBy(array('id' => $entity->getIdTask()));
                    $em->remove($task);
                    $entity->setIdTask(null);

                }

                else {
                    if($entity->getIdTask() != null) {
                        $task = $em->getRepository('SfTodoBundle:Task')->findOneBy(array('id' => $entity->getIdTask()));
                    }
                    else {
                        $task = new Task();
                    }
                    $task->setName($entity->getName());
                    $task->setDeadline($entity->getDeadline());
                    $task->setCreatedBy($user->getId());
                    $task->setCreationDate($entity->getCreationDate());
                    $task->setModificationDate($entity->getModificationDate());
                    $foyers[$user->getCurrentFoyer()]->addTask($task);
                    $task->setVisible(false);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($task);
                    $em->flush();

                    $entity->setIdTask($task->getId());
                }

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('shoppinglist_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('SfShoppingBundle:ShoppingList:editPrivate.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }
}
