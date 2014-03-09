<?php

namespace Sf\TodoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sf\TodoBundle\Entity\Task;
use Sf\TodoBundle\Entity\TaskRepository;
use Sf\TodoBundle\Form\TaskType;
use Sf\TodoBundle\Form\TaskEditType;
use Sf\ShoppingBundle\Form\ArticleType;

/**
 * Task controller.
 *
 */
class TaskController extends Controller
{

    /**
     * Lists all Task entities.
     *
     */
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $foyers = $user->getFoyers();

        $entities = $em->getRepository('SfTodoBundle:Task')->findBy(array('foyer' => $foyers[$user->getCurrentFoyer()], 'visible' => true));

        return $this->render('SfTodoBundle:Task:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function calendarAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $foyers = $user->getFoyers();

        $date = new \DateTime("now");
        $date->modify('first day of this month');

        $entities = $em->getRepository('SfTodoBundle:Task')->getSelectList($foyers[$user->getCurrentFoyer()], $date->format('m'), $date->format('Y'));

        return $this->render('SfTodoBundle:Task:calendar.html.twig', array(
            'entities' => $entities,
            'date' => $date,
        ));
    }

    public function calendarNextAction($month, $year)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $foyers = $user->getFoyers();

        if ($month < 12) {
            $month = $month + 1;
        }
        else {
            $month = 1;
            $year = $year + 1;
        }

        $entities = $em->getRepository('SfTodoBundle:Task')->getSelectList($foyers[$user->getCurrentFoyer()], $month, $year);

        $date = new \DateTime();
        $date->setDate($year, $month, '1');

        return $this->render('SfTodoBundle:Task:calendar.html.twig', array(
            'entities' => $entities,
            'date' => $date,
        ));
    }

    public function calendarPreviousAction($month, $year)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $foyers = $user->getFoyers();

        if ($month > 1) {
            $month = $month - 1;
        }
        else {
            $month = 12;
            $year = $year - 1;
        }

        $entities = $em->getRepository('SfTodoBundle:Task')->getSelectList($foyers[$user->getCurrentFoyer()], $month, $year);

        $date = new \DateTime();
        $date->setDate($year, $month, '1');

        return $this->render('SfTodoBundle:Task:calendar.html.twig', array(
            'entities' => $entities,
            'date' => $date,
        ));
    }

    public function newAction($visible)
  {
    $entity = new Task();

    // On crée le formulaire grâce à l'ArticleType
    $user = $this->container->get('security.context')->getToken()->getUser();
    $foyers = $user->getFoyers();
    $form = $this->createForm(new TaskType($foyers[$user->getCurrentFoyer()]), $entity);

    // On récupère la requête
    $request = $this->getRequest();

    // On vérifie qu'elle est de type POST
    if ($request->getMethod() == 'POST') {
      // On fait le lien Requête <-> Formulaire
      $form->bind($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {

            $entity->setCreationDate(new \DateTime());
            $entity->setModificationDate(new \DateTime());
            $entity->setCreatedBy($user->getId());
            $entity->setVisible($visible);
            $foyers[$user->getCurrentFoyer()]->addTask($entity);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('task_show', array('id' => $entity->getId())));
      }
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau

    return $this->render('SfTodoBundle:Task:new.html.twig', array(
      'form' => $form->createView(),
      'visible' => $visible,
    ));
  }

    /**
     * Finds and displays a Task entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfTodoBundle:Task')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        return $this->render('SfTodoBundle:Task:show.html.twig', array(
            'entity'      => $entity));
    }

    /**
     * Finds and displays a ShoppingList entity.
     *
     */
    public function showEventAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfTodoBundle:Task')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        else {

            $shopping = $em->getRepository('SfShoppingBundle:ShoppingList')->findOneBy(array('idTask' => $id));
                if (!$shopping) {
                    return $this->render('SfTodoBundle:Task:show.html.twig', array(
                        'entity'      => $entity));
                }
                else {
                    $form = $this->createForm(new ArticleType());
                    return $this->render('SfShoppingBundle:ShoppingList:show.html.twig', array(
                        'form'   => $form->createView(),
                        'entity'      => $shopping
                        ));
                }
            }
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfTodoBundle:Task')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('SfTodoBundle:Task:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Task entity.
    *
    * @param Task $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Task $entity)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();
        $form = $this->createForm(new TaskEditType($foyers[$user->getCurrentFoyer()]), $entity, array(
            'action' => $this->generateUrl('task_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Task entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfTodoBundle:Task')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setModificationDate(new \DateTime());
            $em->flush();

            return $this->redirect($this->generateUrl('task_show', array('id' => $id)));
        }

        return $this->render('SfTodoBundle:Task:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Task entity.
     *
     */
    public function deleteAction(Task $id)
    {
        $form = $this->createFormBuilder()->getForm();

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
      $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->container->get('security.context')->getToken()->getUser();
            $foyers = $user->getFoyers();

            $entity = $em->getRepository('SfTodoBundle:Task')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Task entity.');
            }
            $visible = $entity->getVisible();

            $em->remove($entity);
            $em->flush();
            if ($visible == true) {
                return $this->redirect($this->generateUrl('task'));
            }
            else {
                return $this->redirect($this->generateUrl('task_calendar'));
            }
        }
    }
    return $this->render('SfTodoBundle:Task:supprimer.html.twig', array(
      'entity' => $id,
      'form'    => $form->createView()
    ));

    }
}
