<?php

namespace Sf\ContactBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sf\ContactBundle\Entity\Contact;
use Sf\UserBundle\Entity\Foyer;
use Sf\ContactBundle\Form\ContactType;
use Sf\ContactBundle\Form\SearchContactType;

/**
 * Contact controller.
 *
 */
class ContactController extends Controller
{

    /**
     * Lists all Contact entities.
     *
     */
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $foyers = $user->getFoyers();
        $entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);

        return $this->render('SfContactBundle:Contact:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Contact entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Contact();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $foyers = $user->getFoyers();

            $entity->setCreationDate(new \DateTime());
            $entity->setModificationDate(new \DateTime());
            $entity->setAddBy($user->getId());
            $foyers[$user->getCurrentFoyer()]->addContact($entity);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('contact_show', array('id' => $entity->getId())));
        }

        return $this->render('SfContactBundle:Contact:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Contact entity.
    *
    * @param Contact $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Contact $entity)
    {
        $form = $this->createForm(new ContactType(), $entity, array(
            'action' => $this->generateUrl('contact_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Contact entity.
     *
     */
    public function newAction()
    {
        $entity = new Contact();
        $form   = $this->createCreateForm($entity);

        return $this->render('SfContactBundle:Contact:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Contact entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfContactBundle:Contact')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfContactBundle:Contact:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    public function searchAction()
    {
        $form = $this->createForm(new SearchContactType());

        $request = $this->getRequest();
        $requestInArray = $this->getRequest()->request->get('searchcontact');

        if ($request->getMethod() == 'POST') {
            $form->bind($requestInArray);
            if ($form->isValid())
            {

        $search = $requestInArray['recherche'];

        $em = $this->getDoctrine()->getManager();

            if($search != '')
            {
                     $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

                   $qb = $em->createQueryBuilder();

                   $qb->select('a')
                      ->from('SfContactBundle:Contact', 'a')
                      ->where("a.foyer = :foyer AND (a.name LIKE :recherche OR a.category LIKE :recherche OR a.homePhoneNumber LIKE :recherche OR a.mobilePhoneNumber LIKE :recherche OR a.otherPhoneNumber LIKE :recherche)")
                      ->orderBy('a.name', 'ASC')
                      ->setParameter('recherche', '%'.$search.'%')
                      ->setParameter('foyer', $foyers[$user->getCurrentFoyer()]);

                   $query = $qb->getQuery();               
                   $entities = $query->getResult();
            }
            else {
                $entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);
            }

            return $this->render('SfContactBundle:Contact:index.html.twig', array(
            'entities' => $entities,
        ));
        }
        }

        else {
            return $this->render('SfContactBundle:Contact:search.html.twig', array(
            'form'   => $form->createView(),
        ));
        }
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfContactBundle:Contact')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfContactBundle:Contact:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Contact entity.
    *
    * @param Contact $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Contact $entity)
    {
        $form = $this->createForm(new ContactType(), $entity, array(
            'action' => $this->generateUrl('contact_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Contact entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfContactBundle:Contact')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setModificationDate(new \DateTime());
            $em->flush();

            return $this->redirect($this->generateUrl('contact_show', array('id' => $entity->getId())));
        }

        return $this->render('SfContactBundle:Contact:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Contact entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->container->get('security.context')->getToken()->getUser();
            $foyers = $user->getFoyers();

            $entity = $em->getRepository('SfContactBundle:Contact')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Contact entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('contact'));
    }

    /**
     * Creates a form to delete a Contact entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contact_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
