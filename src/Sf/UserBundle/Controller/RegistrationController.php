<?php

namespace Sf\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
public function getDoctrine()
 {
    if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
 }
 
    return $this->container->get('doctrine');
 }

    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $user->setNumberOfConnections(0);
                $user->setRegistrationDate(new \DateTime('now'));
                $user->setCurrentFoyer(0);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $connectedUser = $this->container->get('security.context')->getToken()->getUser();
                if(!is_object($connectedUser))
                {
                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
                    $user->increaseNumberOfConnections();

                    $em = $this->getDoctrine()->getManager();
      $foyers = $em->getRepository('SfUserBundle:Foyer')->getSelectList($user);

      foreach($user->getFoyers() as $uF) {
        $inIt = false;
        foreach ($foyers as $f) {
          if($uF == $f) {
            $inIt = true;
          }
        }
        if($inIt == false) {
          $uF->addUser($user);
          $em->persist($uF);
          $em->flush();
        }
      }

                    $userManager->updateUser($user);
                    return $this->container->get('templating')->renderResponse('FOSUserBundle::bienvenue.html.twig');
                }

                 return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:confirmed.html.'.$this->getEngine(), array(
            'user' => $user, 'message' => 'Vous êtes bien inscrit !'));
                //return $response;
            }
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }
}