<?php

namespace Sf\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
	public function getSelectList($foyer)
  {
    $qb = $this->createQueryBuilder('a');

  	$qb->join('a.foyers', 'f')
  	->where('f.id = :foyer')
    ->setParameter('foyer', $foyer);

    // Et on retourne simplement le QueryBuilder, et non la Query, attention
    return $qb;
  }
}
