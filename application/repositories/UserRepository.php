<?php
namespace Repositories;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{	
	public function login($email, $pass) {
		$qb = $this->_em->createQueryBuilder();
		$q = $qb->select('u')
		->from('entities\User', 'u')
		->where('u.email = :email')
		->andWhere('u.password = :pass')
		->setParameter('email', $email)
		->setParameter('pass', $pass);
		return $qb->getQuery()->getResult();
	}
	
	public function searchUsers ($search) 
	{	
		$qb = $this->_em->createQueryBuilder();
		$qb->select('u')
		->from('entities\User', 'u')
		->where('u.email LIKE :search')
		->orWhere('u.firstName LIKE :search')
		->orWhere('u.lastName LIKE :search')
		->setParameter('search', '%' . $search . '%');
		return $qb->getQuery()->getResult();
	}	
} 