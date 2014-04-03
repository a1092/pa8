<?php

namespace Sf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sf\UserBundle\Entity\Foyer;
use Sf\UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SfUserBundle::index.html.twig');
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
        return $this->render('SfUserBundle::index.html.twig');
      }
    }
}