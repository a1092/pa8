<?php

namespace Sf\ChatBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MessageRepository extends EntityRepository
{
	public function getMessages($chat, $nombreParPage, $page)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('a')
		->from('SfChatBundle:Message', 'a')
		->where('a.chat = :chat')
		->setParameter('chat', $chat)
		->orderBy('a.sentDate', 'DESC')
		->getQuery();

	    // On définit l'article à partir duquel commencer la liste
		$qb->setFirstResult(($page-1) * $nombreParPage)
	    // Ainsi que le nombre d'articles à afficher
		->setMaxResults($nombreParPage);

	    // Enfin, on retourne l'objet Paginator correspondant à la requête construite
	    // (n'oubliez pas le use correspondant en début de fichier)
		return new Paginator($qb);
	}
}
