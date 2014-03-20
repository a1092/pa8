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
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        //Dans ton JSON, il faut : ID user
        $id = $params->id;

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('SfUserBundle:User')->findById($id);
        $foyers = $user->getFoyers();

        $entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);

        return new JsonResponse(array('entities' => $entities));
    }

    /**
     * Creates a new Contact entity.
     *
     */
    public function newAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        //Dans ton JSON, il faut : ID user
        $id = $params->id;

        $name = $params->name;
        $email = $params->email;
        $address = $params->address;
        $homePhoneNumber = $params->homePhoneNumber;
        $mobilePhoneNumber = $params->mobilePhoneNumber;
        $otherPhoneNumber = $params->otherPhoneNumber;
        $remark = $params->remark;
        $category = $params->category;

        $contact = new Contact;
        $contact->setName($name);
        if ($email != '') {
            $contact->setEmail($email);
        }
        if ($address != '') {
            $contact->setAddress($address);
        }
        if ($homePhoneNumber != '') {
            $contact->setHomePhoneNumber($homePhoneNumber);
        }
        if ($mobilePhoneNumber != '') {
            $contact->setMobilePhoneNumber($mobilePhoneNumber);
        }
        if ($otherPhoneNumber != '') {
            $contact->setOtherPhoneNumber($otherPhoneNumber);
        }
        if ($remark != '') {
            $contact->setRemark($remark);
        }
        if ($category != '') {
            $contact->setCategory($category);
        }

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('SfUserBundle:User')->findById($id);
        $foyers = $user->getFoyers();

        $contact->setCreationDate(new \DateTime());
        $contact->setModificationDate(new \DateTime());
        $contact->setAddBy($user->getId());

        $foyers[$user->getCurrentFoyer()]->addContact($entity);

        $em->persist($contact);
        $em->flush();

        $entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);

        return new JsonResponse(array('entities' => $entities));
    }

    /**
     * Edits a new Contact entity.
     *
     */
    public function editAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        //Dans ton JSON, il faut : ID user
        $idUser = $params->idUser;
        //Il faut : ID contact
        $idContact = $params->idContact;

        $name = $params->name;
        $email = $params->email;
        $address = $params->address;
        $homePhoneNumber = $params->homePhoneNumber;
        $mobilePhoneNumber = $params->mobilePhoneNumber;
        $otherPhoneNumber = $params->otherPhoneNumber;
        $remark = $params->remark;
        $category = $params->category;

        $contact = $em->getRepository('SfContactBundle:Bundle')->findById($idContact);
        $contact->setName($name);
        if ($email != '') {
            $contact->setEmail($email);
        }
        if ($address != '') {
            $contact->setAddress($address);
        }
        if ($homePhoneNumber != '') {
            $contact->setHomePhoneNumber($homePhoneNumber);
        }
        if ($mobilePhoneNumber != '') {
            $contact->setMobilePhoneNumber($mobilePhoneNumber);
        }
        if ($otherPhoneNumber != '') {
            $contact->setOtherPhoneNumber($otherPhoneNumber);
        }
        if ($remark != '') {
            $contact->setRemark($remark);
        }
        if ($category != '') {
            $contact->setCategory($category);
        }

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('SfUserBundle:User')->findById($id);
        $foyers = $user->getFoyers();

        $contact->setModificationDate(new \DateTime());

        $foyers[$user->getCurrentFoyer()]->addContact($entity);

        $em->persist($contact);
        $em->flush();

        $entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);

        return new JsonResponse(array('entities' => $entities));
    }

    /**
     * Deletes a Contact entity.
     *
     */
    public function deleteAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        //Dans ton JSON, il faut : ID user
        $idUser = $params->idUser;
        //Il faut : ID contact
        $idContact = $params->idContact;

        $entity = $em->getRepository('SfContactBundle:Contact')->findOneBy(array('id' => $idContact));
        $em->remove($entity);
        $em->flush();

        $user = $em->getRepository('SfUserBundle:User')->findById($idUser);
        $foyers = $user->getFoyers();

        $entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);
        return new JsonResponse(array('entities' => $entities));
    }

}