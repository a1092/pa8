<?php

namespace Sf\TodoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends EntityRepository
{
	public function getCalendarList($foyer, $month, $year, $user)
  {
    $qb = $this->_em->createQueryBuilder();

    $startmonth = new \DateTime();
    $startmonth->setDate($year, $month, '1');
    $endmonth = new \DateTime();
    $endmonth->setDate($year, ($month+1), '1');


  	$qb->select('a')
  	   ->from('SfTodoBundle:Task', 'a')
       ->leftjoin('a.users', 'u')
  	   ->where('a.foyer = :foyer')
       ->setParameter('foyer', $foyer)
       ->andWhere('a.createdBy = :createdBy or u = :user')
       ->setParameter('createdBy', $user->getId())
       ->setParameter('user', $user)
       ->andWhere('a.deadline >= :startmonth')
       ->setParameter('startmonth', $startmonth)
       ->andWhere('a.deadline < :endmonth')
       ->setParameter('endmonth', $endmonth)
       ->orderBy('a.deadline', 'ASC')
       ;

    // Et on retourne simplement le QueryBuilder, et non la Query, attention
    return $qb->getQuery()
              ->getResult();
  }


  public function getPersonnalList($foyer, $user)
  {
    $qb = $this->_em->createQueryBuilder();

    $qb->select('a')
    ->from('SfTodoBundle:Task', 'a')
    ->leftjoin('a.users', 'u')
    ->where('a.foyer = :foyer')
    ->setParameter('foyer', $foyer)
    ->andWhere('a.visible = true')
    ->andWhere('a.createdBy = :createdBy or u = :user')
    ->setParameter('createdBy', $user->getId())
    ->setParameter('user', $user)
    ->orderBy('a.deadline', 'ASC')
    ;

    return $qb->getQuery()
              ->getResult();
  }
}