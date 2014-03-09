<?php

namespace Sf\ShoppingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sf\ShoppingBundle\Entity\ShoppingList;
use Sf\ShoppingBundle\Entity\Article;
use Sf\ShoppingBundle\Form\ArticleType;
use Sf\TodoBundle\Entity\Task;

/**
 * ShoppingList controller.
 *
 */
class ArticleController extends Controller
{
	/**
     * Creates a new ShoppingList entity.
     *
     */
    public function newAction($id, $page)
    {
        $entity = new Article;
        // On crée le formulaire grâce à l'ArticleType
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $em = $this->getDoctrine()->getManager();
        $shoppingList = $em->getRepository('SfShoppingBundle:ShoppingList')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$shoppingList) {
            throw $this->createNotFoundException('Unable to find ShoppingList entity.');
        }

        $form = $this->createForm(new ArticleType, $entity);

        // On récupère la requête
        $request = $this->get('request');

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
          // On fait le lien Requête <-> Formulaire
          $form->bind($request);

            if ($form->isValid()) {
                $shoppingList->setModificationDate(new \DateTime());
                $entity->setAddBy($user->getId());
                $shoppingList->addArticle($entity);

                $em = $this->getDoctrine()->getManager();
                $em->persist($shoppingList);
                $em->persist($entity);
                $em->flush();

                if($page == 'show') {
                    return $this->redirect($this->generateUrl('shoppinglist_show', array('id' => $id, 'form'   => $form->createView())));
                }
                else {
                    return $this->redirect($this->generateUrl('shoppinglist'));
                }
            }
        }

        return $this->render('SfShoppingBundle:ShoppingList:show.html.twig', array(
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

        $entity = $em->getRepository('SfShoppingBundle:Article')->findOneBy(array('id' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $form = $this->createForm(new ArticleType(), $entity);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $entity->getShoppingList()->setModificationDate(new \DateTime());

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('shoppinglist_show', array('id' => $entity->getShoppingList()->getId())));
            }
        }

        return $this->render('SfShoppingBundle:ShoppingList:editA.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function deleteAction(Article $id)
    {
        $form = $this->createFormBuilder()->getForm();

        $request = $this->getRequest();

		if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $user = $this->container->get('security.context')->getToken()->getUser();
                $foyers = $user->getFoyers();

                $entity = $em->getRepository('SfShoppingBundle:Article')->findOneBy(array('id' => $id));
                $shoppingList = $entity->getShoppingList()->getId();

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Article entity.');
                }

                $em->remove($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('shoppinglist_show', array('id' => $shoppingList)));
            }
        }

        return $this->render('SfShoppingBundle:ShoppingList:supprimerA.html.twig', array(
          'entity' => $id,
          'shoppingList' => $id->getShoppingList()->getId(),
          'form'    => $form->createView()
        ));

    }
}