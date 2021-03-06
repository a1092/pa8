<?php

namespace Sf\DashboardBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
	public function load($lastcheck, $foyer) {
	
		$qb = $this->_em->createQueryBuilder();

		$qb->select('a')
		->from('SfDashboardBundle:Post', 'a')
		->leftjoin('a.foyer', 'f')

		->where('a.creationDate >= :lastcheck')
		->setParameter('lastcheck', $lastcheck)
		->andwhere('f = :foyer')
		->setParameter('foyer', $foyer)
		
		->orderBy('a.creationDate', 'ASC')
		;
		
		
	      return $qb->getQuery()
              ->getResult();
	}
}
