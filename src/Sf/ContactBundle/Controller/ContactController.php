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
        $search = $this->createForm(new SearchContactType());

        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $foyers = $user->getFoyers();
        $entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);

        return $this->render('SfContactBundle:Contact:index.html.twig', array(
            'entities' => $entities,
            'search'   => $search->createView(),
        ));
    }
    /**
     * Creates a new Contact entity.
     *
     */
    public function newAction()
    {
        $entity = new Contact;

        // On crée le formulaire grâce à l'ArticleType
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();
        $form = $this->createForm(new ContactType, $entity);

        // On récupère la requête
        $request = $this->get('request');

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
          // On fait le lien Requête <-> Formulaire
          $form->bind($request);

            if ($form->isValid()) {

                $entity->setCreationDate(new \DateTime());
                $entity->setModificationDate(new \DateTime());
                $entity->setAddBy($user->getId());
                $foyers[$user->getCurrentFoyer()]->addContact($entity);
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('contact_show', array('id' => $entity->getId())));
            }
        }

        $search = $this->createForm(new SearchContactType());
        return $this->render('SfContactBundle:Contact:new.html.twig', array(
            'form'   => $form->createView(),
            'search'   => $search->createView(),
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

        $search = $this->createForm(new SearchContactType());
        return $this->render('SfContactBundle:Contact:show.html.twig', array(
            'entity'      => $entity,
            'search'   => $search->createView(),
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
            'search'   => $form->createView(),
        ));
        }
        }

        else {
            return $this->render('SfContactBundle:Contact:index.html.twig', array(
            'search'   => $form->createView(),
        ));
        }
    }

    /**
     * Edits an existing Contact entity.
     *
     */
    public function editAction($id)
    {
        $search = $this->createForm(new SearchContactType());
        
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfContactBundle:Contact')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $form = $this->createForm(new ContactType(), $entity);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $entity->setModificationDate(new \DateTime());
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('contact_show', array('id' => $entity->getId(), 'search'   => $search->createView())));
            }
        }

        return $this->render('SfContactBundle:Contact:edit.html.twig', array(
            'entity'      => $entity,
            'form' => $form->createView(),
            'search'   => $search->createView(),
        ));
    }
    
    /**
     * Deletes a Contact entity.
     *
     */
    public function deleteAction(Contact $id)
    {
        $search = $this->createForm(new SearchContactType());
        $form = $this->createFormBuilder()->getForm();

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

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
                return $this->redirect($this->generateUrl('contact', array('search'   => $search->createView())));
            }
    }
    
    return $this->render('SfContactBundle:Contact:supprimer.html.twig', array(
      'entity' => $id,
      'form'    => $form->createView(),
      'search'   => $search->createView(),
    ));

    }
}
