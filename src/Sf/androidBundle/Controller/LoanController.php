<?php

namespace Sf\androidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sf\ContactBundle\Entity\Contact;
use Sf\UserBundle\Entity\UserRepository;
use Sf\UserBundle\Entity\Foyer;
use Sf\LoanBundle\Entity\Loan;
use Sf\ContactBundle\Form\ContactType;
use Sf\ContactBundle\Form\SearchContactType;

class LoanController extends Controller
{
    public function getLoansAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
        $id = $params->id;

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('SfUserBundle:User')->findOneById($id);
        $foyers = $user->getFoyers();

		$entities = $em->getRepository('SfLoanBundle:Loan')->getPersonnalList($foyers[$user->getCurrentFoyer()], $user);
		
		foreach($entities as $loan){
		    $tab = array('loanId' => $loan->getId(),
			'object' => $loan->getItem(),
			'idLender' => $em->getRepository('SfUserBundle:User')->findOneById($loan->getLender())->getId(),
			'nameLender' => $em->getRepository('SfUserBundle:User')->findOneById($loan->getLender())->getUsername(),
			'idBorrower' => $em->getRepository('SfUserBundle:User')->findOneById($loan->getBorrower())->getId(),
			'nameBorrower' => $em->getRepository('SfUserBundle:User')->findOneById($loan->getBorrower())->getUsername(),
			);
			
		    $tabLoans[] = $tab;
		    
           $tab = array();
		}

		$entities = $em->getRepository('SfUserBundle:User')->getUserList($foyers[$user->getCurrentFoyer()]);
		foreach($entities as $user){
			$tab = array('userId' => $user->getId(),
			'userName' => $user->getUsername(),
			);
			
		    $tabUsers[] = $tab;
		    
           $tab = array();
		}
		
        return new JsonResponse(array('tabLoans' => $tabLoans,
		'tabUsers' => $tabUsers));
    }

    public function newLoanAction()
    {
        $content = $this->get("request")->getContent();
        $params = json_decode($content);
		
		
        $id = $params->userId;
		
		$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SfUserBundle:User')->findOneById($id);
        $foyers = $user->getFoyers();

        $loanObject = $params->loanObject;
		$lender = $em->getRepository('SfUserBundle:User')->findOneById($params->idLender);
		$borrower = $em->getRepository('SfUserBundle:User')->findOneById($params->idBorrower);
		
        $loan = new Loan;
		
        $loan->setItem($loanObject);
        $loan->setLender($lender);
        $loan->setBorrower($borrower);
        $loan->setCreationDate(new \DateTime());
        $loan->setModificationDate(new \DateTime());
        $loan->setCreatedBy($user->getId());
        $loan->setFoyer($foyers[$user->getCurrentFoyer()]);

		$loan->getBorrower()->addBorrowedThing($loan);
		$loan->getLender()->addLentThing($loan);
		
        $em->persist($loan);
        $em->flush();

		$entities = $em->getRepository('SfLoanBundle:Loan')->getPersonnalList($foyers[$user->getCurrentFoyer()], $user);
		
		foreach($entities as $loan){
		    $tab = array('loanId' => $loan->getId(),
			'object' => $loan->getItem(),
			'idLender' => $em->getRepository('SfUserBundle:User')->findOneById($loan->getLender())->getId(),
			'nameLender' => $em->getRepository('SfUserBundle:User')->findOneById($loan->getLender())->getUsername(),
			'idBorrower' => $em->getRepository('SfUserBundle:User')->findOneById($loan->getBorrower())->getId(),
			'nameBorrower' => $em->getRepository('SfUserBundle:User')->findOneById($loan->getBorrower())->getUsername(),
			);
			
		    $tabLoans[] = $tab;
		    
           $tab = array();
		}

		$entities = $em->getRepository('SfUserBundle:User')->getUserList($foyers[$user->getCurrentFoyer()]);
		foreach($entities as $user){
			$tab = array('userId' => $user->getId(),
			'userName' => $user->getUsername(),
			);
			
		    $tabUsers[] = $tab;
		    
           $tab = array();
		}
		
		$final = array('tabLoans' => $tabLoans,
		'tabUsers' => $tabUsers,
		);
		
        return new JsonResponse(array('tabLoans' => $tabLoans,
		'tabUsers' => $tabUsers));
    }

}