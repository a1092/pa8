<?php

namespace Sf\ShoppingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sf\ShoppingBundle\Entity\ShoppingList;
use Sf\ShoppingBundle\Form\ShoppingListType;
use Sf\ShoppingBundle\Form\ShoppingListEditType;
use Sf\TodoBundle\Entity\Task;

/**
 * ShoppingList controller.
 *
 */
class ShoppingListController extends Controller
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

        $entities = $em->getRepository('SfShoppingBundle:ShoppingList')->findByFoyer($foyers[$user->getCurrentFoyer()]);

        return $this->render('SfShoppingBundle:ShoppingList:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ShoppingList entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ShoppingList();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $foyers = $user->getFoyers();

            $entity->setCreationDate(new \DateTime());
            $entity->setModificationDate(new \DateTime());
            $entity->setCreatedBy($user->getId());
            $foyers[$user->getCurrentFoyer()]->addShoppingList($entity);

            foreach($entity->getArticles() as $article)
            {
                $article->setAddBy($user->getId());
                $article->setShoppingList($entity);
            }

            if($entity->getDeadline() != null) {
                $task = new Task();
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

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('shoppinglist_show', array('id' => $entity->getId())));
        }

        return $this->render('SfShoppingBundle:ShoppingList:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a ShoppingList entity.
    *
    * @param ShoppingList $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(ShoppingList $entity)
    {
        $form = $this->createForm(new ShoppingListType(), $entity, array(
            'action' => $this->generateUrl('shoppinglist_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ShoppingList entity.
     *
     */
    public function newAction()
    {
        $entity = new ShoppingList();
        $form   = $this->createCreateForm($entity);

        return $this->render('SfShoppingBundle:ShoppingList:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ShoppingList entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfShoppingBundle:ShoppingList')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ShoppingList entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfShoppingBundle:ShoppingList:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing ShoppingList entity.
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

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfShoppingBundle:ShoppingList:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ShoppingList entity.
    *
    * @param ShoppingList $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ShoppingList $entity)
    {
        $form = $this->createForm(new ShoppingListEditType(), $entity, array(
            'action' => $this->generateUrl('shoppinglist_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ShoppingList entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfShoppingBundle:ShoppingList')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ShoppingList entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $entity->setModificationDate(new \DateTime());

            foreach($entity->getArticles() as $article)
            {
                if($article->getAddBy() == NULL)
                    {
                        $article->setAddBy($user->getId());
                    }
            }

            $em->flush();

            return $this->redirect($this->generateUrl('shoppinglist_show', array('id' => $entity->getId())));
        }

        return $this->render('SfShoppingBundle:ShoppingList:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ShoppingList entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SfShoppingBundle:ShoppingList')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ShoppingList entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('shoppinglist'));
    }

    /**
     * Creates a form to delete a ShoppingList entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('shoppinglist_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
