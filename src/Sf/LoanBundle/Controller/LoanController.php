<?php
namespace Sf\LoanBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sf\LoanBundle\Entity\Loan;
use Sf\LoanBundle\Entity\LoanRepository;
use Sf\LoanBundle\Form\LoanType;

/**
 * Loan controller.
 *
 */
class LoanController extends Controller
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

        //$entities = $em->getRepository('SfLoanBundle:Loan')->findBy(array('foyer' => $foyers[$user->getCurrentFoyer()]));
        $entities = $em->getRepository('SfLoanBundle:Loan')->getPersonnalList($foyers[$user->getCurrentFoyer()], $user);

        return $this->render('SfLoanBundle:Loan:index.html.twig', array(
            'entities' => $entities,
        ));
    }

	/**
     * Creates a new Loan entity.
     *
     */
    public function newAction()
    {
        $entity = new Loan;
        // On crée le formulaire grâce à l'ArticleType
        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new LoanType($foyers[$user->getCurrentFoyer()]), $entity);

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
                $foyers[$user->getCurrentFoyer()]->addLoan($entity);

                $entity->getBorrower()->addBorrowedThing($entity);
                $entity->getLender()->addLentThing($entity);

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('loan_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('SfLoanBundle:Loan:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Loan entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $foyers = $user->getFoyers();

        $entity = $em->getRepository('SfLoanBundle:Loan')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Loan entity.');
        }

        return $this->render('SfLoanBundle:Loan:show.html.twig', array(
            'entity'      => $entity));
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

        $entity = $em->getRepository('SfLoanBundle:Loan')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Loan entity.');
        }

        $form = $this->createForm(new LoanType($foyers[$user->getCurrentFoyer()]), $entity);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $entity->setModificationDate(new \DateTime());

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('loan_show', array('id' => $id)));
            }
        }

        return $this->render('SfLoanBundle:Loan:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function deleteAction(Loan $id)
    {
        $form = $this->createFormBuilder()->getForm();

        $request = $this->getRequest();

		if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $user = $this->container->get('security.context')->getToken()->getUser();
                $foyers = $user->getFoyers();

                $entity = $em->getRepository('SfLoanBundle:Loan')->findOneBy(array('id' => $id, 'foyer' => $foyers[$user->getCurrentFoyer()]));

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Loan entity.');
                }

                $em->remove($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('loan'));
            }
        }

        return $this->render('SfLoanBundle:Loan:supprimer.html.twig', array(
          'entity' => $id,
          'form'    => $form->createView()
        ));

    }
}