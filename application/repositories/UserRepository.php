<?php
namespace Repositories;
use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{

	public function changePass($email, $pass)
	{
		$qb = $this->_em->createQueryBuilder();
		$q = $qb->update('entities\User', 'u')
		->set('u.password', $qb->expr()->literal(md5($pass)))
		->where('u.email = :email')
		->setParameter('email', $email)
		->getQuery();
		$q->execute();
	}
	
	public function getUserByEmail($email) 
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('u')
		->from('entities\User', 'u')
		->where('u.email = :email')
		->setParameter('email', $email);
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
	
	public function updateUser($user)
	{
		$qb = $this->_em->createQueryBuilder();
		$q = $qb->update('entities\User', 'u')
		->set('u.password', $qb->expr()->literal($user->getPass()))
		->set('u.email', $qb->expr()->literal($user->getEmail()))
		->set('u.firstName', $qb->expr()->literal($user->getFirstName()))
		->set('u.lastName', $qb->expr()->literal($user->getLastName()))
		->where('u.id = :id')
		->setParameter('id', $user->getId())
		->getQuery();
		$q->execute();
		
	}
	
	public function delete($id) 
	{
		$qb = $this->_em->createQueryBuilder();
		$q = $qb->delete('entities\User', 'u')
		->where('u.id = :id')
		->setParameter('id', $id)
		->getQuery();
		$q->execute();
	}
	
	
	
	
	
} 