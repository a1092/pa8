<?php

namespace Sf\androidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sf\ContactBundle\Entity\Contact;
use Sf\UserBundle\Entity\Foyer;
use Sf\ContactBundle\Form\ContactType;
use Sf\ContactBundle\Form\SearchContactType;

class ContactController extends Controller
{

    public function getContactsAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        $id = $params->id;

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('SfUserBundle:User')->findOneById($id);
        $foyers = $user->getFoyers();

        $entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);
		
		foreach($entities as $contact){
		    $tab = array('contactId' => $contact->getId(),
			'contactName' => $contact->getName(),
			'contactCategory' => $contact->getCategory(),
			'contactEmail' => $contact->getEmail(),
			'contactAddress' => $contact->getAddress(),
			'contactHomePhoneNumber' => $contact->getHomePhoneNumber(),
			'contactMobilePhoneNumber' => $contact->getMobilePhoneNumber(),
			'contactOtherPhoneNumber' => $contact->getOtherPhoneNumber(),
			'contactRemark' => $contact->getRemark(),
			);
		    $final[] = $tab;
		    
           $tab = array();
		}
        return new JsonResponse(array('entities' => $final));
    }

    public function newContactAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
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

        $user = $em->getRepository('SfUserBundle:User')->findOneById($id);
        $foyers = $user->getFoyers();

        $contact->setCreationDate(new \DateTime());
        $contact->setModificationDate(new \DateTime());
        $contact->setAddBy($user->getId());

        $foyers[$user->getCurrentFoyer()]->addContact($contact);

        $em->persist($contact);
        $em->flush();

        $entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);
		
		foreach($entities as $contact){
		    $tab = array('contactId' => $contact->getId(),
			'contactName' => $contact->getName(),
			'contactCategory' => $contact->getCategory(),
			'contactEmail' => $contact->getEmail(),
			'contactAddress' => $contact->getAddress(),
			'contactHomePhoneNumber' => $contact->getHomePhoneNumber(),
			'contactMobilePhoneNumber' => $contact->getMobilePhoneNumber(),
			'contactOtherPhoneNumber' => $contact->getOtherPhoneNumber(),
			'contactRemark' => $contact->getRemark(),
			);
		    $final[] = $tab;
		    
           $tab = array();
		}
        return new JsonResponse(array('entities' => $final));
    }

    public function editContactAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        $idUser = $params->idUser;
        $idContact = $params->idContact;

        $name = $params->name;
        $email = $params->email;
        $address = $params->address;
        $homePhoneNumber = $params->homePhoneNumber;
        $mobilePhoneNumber = $params->mobilePhoneNumber;
        $otherPhoneNumber = $params->otherPhoneNumber;
        $remark = $params->remark;
        $category = $params->category;

        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('SfContactBundle:Contact')->findOneById($idContact);
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
		
        $user = $em->getRepository('SfUserBundle:User')->findOneById($idUser);
        $foyers = $user->getFoyers();

        $contact->setModificationDate(new \DateTime());

        $foyers[$user->getCurrentFoyer()]->addContact($contact);

        $em->persist($contact);
        $em->flush();

        $entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);
		
		foreach($entities as $contact){
		    $tab = array('contactId' => $contact->getId(),
			'contactName' => $contact->getName(),
			'contactCategory' => $contact->getCategory(),
			'contactEmail' => $contact->getEmail(),
			'contactAddress' => $contact->getAddress(),
			'contactHomePhoneNumber' => $contact->getHomePhoneNumber(),
			'contactMobilePhoneNumber' => $contact->getMobilePhoneNumber(),
			'contactOtherPhoneNumber' => $contact->getOtherPhoneNumber(),
			'contactRemark' => $contact->getRemark(),
			);
		    $final[] = $tab;
		    
           $tab = array();
		}
        return new JsonResponse(array('entities' => $final));
    }

    public function deleteContactAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        $idUser = $params->idUser;
        $idContact = $params->idContact;

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SfContactBundle:Contact')->findOneBy(array('id' => $idContact));
        $em->remove($entity);
        $em->flush();
		
		
        $user = $em->getRepository('SfUserBundle:User')->findOneById($idUser);
        $foyers = $user->getFoyers();
		
		$entities = $em->getRepository('SfContactBundle:Contact')->findByFoyer($foyers[$user->getCurrentFoyer()]);
		
		foreach($entities as $contact){
		    $tab = array('contactId' => $contact->getId(),
			'contactName' => $contact->getName(),
			'contactCategory' => $contact->getCategory(),
			'contactEmail' => $contact->getEmail(),
			'contactAddress' => $contact->getAddress(),
			'contactHomePhoneNumber' => $contact->getHomePhoneNumber(),
			'contactMobilePhoneNumber' => $contact->getMobilePhoneNumber(),
			'contactOtherPhoneNumber' => $contact->getOtherPhoneNumber(),
			'contactRemark' => $contact->getRemark(),
			);
		    $final[] = $tab;
		    
           $tab = array();
		}
        return new JsonResponse(array('entities' => $final));
    }
}