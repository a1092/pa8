<?php

namespace Sf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sf\UserBundle\Entity\Foyer;
use Sf\UserBundle\Entity\User;
use Sf\UserBundle\Form\Type\FoyerEditType;
use Sf\UserBundle\Form\Type\RegistrationFoyerFormType;
use Sf\UserBundle\Form\ChoiceType;
use Symfony\Component\HttpFoundation\Response;

class FoyerController extends Controller
{
  public function indexAction()
  {
    return $this->render('SfUserBundle::options.html.twig');
  }

  public function newAction()
  {
    $foyer = new Foyer();
    $user = $this->container->get('security.context')->getToken()->getUser();
    $form = $this->createForm(new FoyerEditType(), $foyer);

    $request = $this->getRequest();

    if ($request->getMethod() == 'POST') {
      $form->bind($request);

      if ($form->isValid()) {
        $foyers = $user->getFoyers();
        foreach ($foyers as $f) {
          if($f->getName() == $foyer->getName()) {
            return $this->render('SfUserBundle::creerFoyer.html.twig', array(
          'message' => 'Vous avez déjà utilisé ce nom pour un de vos foyers !',
          ));
          }
        }
        $foyer->addUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($foyer);
        $em->flush();

        $count = 0;
        foreach($foyers as $f) {
            $count++;
        }
        $user->setCurrentFoyer($count-1);
        $em->persist($user);
        $em->flush();

        return $this->render('SfUserBundle::options.html.twig', array(
          'message' => 'Foyer crée !',
          ));
      }
    }

    return $this->render('SfUserBundle::creerFoyer.html.twig', array(
      'form'    => $form->createView(),
      'foyer' => $foyer
      ));
  }

  public function editAction(Foyer $foyer)
  {
    $form = $this->createForm(new FoyerEditType(), $foyer);

    $request = $this->getRequest();

    if ($request->getMethod() == 'POST') {
      $form->bind($request);

      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($foyer);
        $em->flush();

        return $this->render('SfUserBundle::options.html.twig', array(
          'message' => 'Nom modifié',
          ));
      }
    }

    return $this->render('SfUserBundle::modifierFoyer.html.twig', array(
      'form'    => $form->createView(),
      'foyer' => $foyer
      ));
  }

  public function newMemberAction(Foyer $foyer)
  {
    $newUser = new User();
    $form = $this->createForm(new RegistrationFoyerFormType(), $newUser);

    $request = $this->getRequest();

    if ($request->getMethod() == 'POST') {
      $form->bind($request);

      if ($form->isValid()) {
        $newUser->setEnabled(true);
        $newUser->addFoyer($foyer);
		$newUser->setRegistrationDate(new \DateTime('now'));
		
        $newUser->setNumberOfConnections(0);
        $newUser->setCurrentFoyer(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($newUser);
        $em->flush();

        // On définit un message flash
        $this->get('session')->getFlashBag()->add('info', 'Membre ajouté');

        return $this->render('SfUserBundle::options.html.twig', array(
          'message' => 'Membre ajouté',
          ));
      }
    }

    return $this->render('SfUserBundle::ajouterMembre.html.twig', array(
      'form'    => $form->createView(),
      'foyer' => $foyer
      ));
  }

  public function changeAction()
  {
    $user = $this->container->get('security.context')->getToken()->getUser();

    /* Première étape : l'utilisateur choisir les personnes avec qui il veut chatter */
    $conv = $this->createForm(new ChoiceType($user));

    $request = $this->getRequest();
    $requestInArray = $this->getRequest()->request->get('foyer_choice');

    if ($request->getMethod() == 'POST') {
      $conv->bind($requestInArray);
      if ($conv->isValid())
      {
        $foyer = $requestInArray['foyer'];

        $foyers = $user->getFoyers();

        $i = 0;

        foreach ($foyers as $f) {
          if($f->getId() == $foyer) {
            $user->setCurrentFoyer($i);
          }
          $i++;
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->render('SfUserBundle::options.html.twig', array(
          'message' => 'Foyer changé',
          ));
      }
    }

    return $this->render('SfUserBundle::changeFoyer.html.twig', array(
      'form'   => $conv->createView(),
      ));
  }
  
	public function membersAction() {
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$foyers = $user->getFoyers();
		
		$members = array();
		$i = 1;
		foreach($foyers as $foyer) {
			
			foreach($foyer->getUsers() as $member) {
			
				if(!in_array($member, $members) && $member != $user) {
					$members[$i] = array(
						"userid" => $member->getId(),
						"username" => $member->getUsername(),
						"firstname" => $member->getFirstname(),
						"lastname" => $member->getLastname(),
						
						);
					$i++;
				}
			}
		
		}
		
		$response = new Response(json_encode(array(
			'members' => $members
		)));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
}