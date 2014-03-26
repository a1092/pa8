<?php

namespace Sf\ChatBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ChatRepository extends EntityRepository
{
	public function getPrivateChat($foyer)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('a')
		->from('SfChatBundle:Chat', 'a')
		->where('a.foyer = :foyer')
		->setParameter('foyer', $foyer)
		->andWhere('a.private = true')
		;


	    return $qb->getQuery()
              ->getResult();
	}
}
