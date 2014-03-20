<?php

namespace Sf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sf\UserBundle\Entity\Foyer;
use Sf\UserBundle\Entity\User;
use Sf\UserBundle\Form\Type\FoyerEditType;
use Sf\UserBundle\Form\Type\RegistrationFoyerFormType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SfUserBundle::layout.html.twig');
    }

    public function bienvenueAction()
    {
      $user = $this->container->get('security.context')->getToken()->getUser();
      $user->increaseNumberOfConnections();

      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();

      if ($user->getNumberOfConnections() == 1)
      {
        return $this->render('SfUserBundle::bienvenue.html.twig');
      }
      else
      {
        return $this->render('SfUserBundle::layout.html.twig');
      }
    }

    public function modifierFoyerAction(Foyer $foyer)
    {
      $form = $this->createForm(new FoyerEditType(), $foyer);

      $request = $this->getRequest();

      if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->persist($foyer);
          $em->flush();

        // On définit un message flash
        $this->get('session')->getFlashBag()->add('info', 'Nom modifié');

        return $this->render('SfUserBundle::layout.html.twig');
      }
    }

    return $this->render('SfUserBundle::modifierFoyer.html.twig', array(
      'form'    => $form->createView(),
      'foyer' => $foyer
    ));
    }

    public function ajouterMembreAction(Foyer $foyer)
    {
      $newUser = new User();
      $form = $this->createForm(new RegistrationFoyerFormType(), $newUser);

      $request = $this->getRequest();

      if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          $newUser->setEnabled(true);
          $newUser->addFoyer($foyer);
          $newUser->setCurrentFoyer(0);
          $em = $this->getDoctrine()->getManager();
          $em->persist($newUser);
          $em->flush();

        // On définit un message flash
        $this->get('session')->getFlashBag()->add('info', 'Membre ajouté');

        return $this->render('SfUserBundle:Registration:confirmed.html.twig');
      }
    }

    return $this->render('SfUserBundle::ajouterMembre.html.twig', array(
      'form'    => $form->createView(),
      'foyer' => $foyer
    ));
    }
}