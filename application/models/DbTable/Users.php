<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{
	//name of the db-table
	protected $_name = 'users';
	
	// here comes the functions addrow / delete / update 
	
	public function getUsers()
	{
		$rows = $this->fetchAll();
		if (!$rows) {
			throw new Exception("Could not find rows");
		}
		
		$users = array();
		foreach($rows as $row) {
			array_push($users, new Application_Model_User($row['id'], $row['firstName'], $row['lastName'], $row['email'], $row['password']));
		}
		return $users;
	}
	

	public function getUser($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
		if (!$row) {
			throw new Exception("Could not find row $id");
		}
		
		return new Application_Model_User($row['id'], $row['firstName'], $row['lastName'], $row['email'], $row['password']);
	}
	
	public function countRows()
	{
		
		$rows = $this->fetchAll();

		return count($rows);
	}
	
	public function checkEmail($email)
	{
		$row = $this->fetchRow(
	    $this->select()
	        ->where('email = ?', $email)
		
	    );
		if (!$row) {
			return false;
		}
	
		return true;
	}
	
	public function changePass($email, $pass){
		$row = $this->fetchRow(
				$this->select()
				->where('email = ?', $email)
		
		);
		
		$this->updateUser(new Application_Model_User($row['id'], $row['firstName'], $row['lastName'], $row['email'], $pass));
	}
	
	public function checkLogin ($user, $pass) 
	{
		$row = $this->fetchRow(
	    $this->select()
	        ->where('email = ?', $user)
			->where('password = ?', md5($pass))
	    );
		//if we have a row returned the user & pass are correct 
		if(!$row) {
			return null;
		} else {
			return new Application_Model_User($row['id'], $row['firstName'], $row['lastName'], $row['email'], $row['password']);
		}
		
		
	}
	
	public function serachUsers($search) 
	{
		$rows = $this->fetchAll(
				$this->select()
				->where('email LIKE ?', '%' . (String)$search . '%')
				->orWhere('firstName LIKE ?', '%' . (String)$search . '%')
				->orWhere('lastName LIKE ?', '%' . (String)$search . '%')
				
			);
		
		
		if (!$rows) {
			throw new Exception("Could not find rows");
		}
		
		$users = array();
		foreach($rows as $row) {
			array_push($users, new Application_Model_User($row['id'], $row['firstName'], $row['lastName'], $row['email'], $row['password']));
		}
		return $users;
	}
	
	public function addUser(Application_Model_User $user)
	{
		$data = array(
				'firstName' => $user->getFirstName(),
				'lastName' => $user->getLastName(),
				'email' => $user->getEmail(),
				'password' =>md5($user->getPass()),
		);
		$this->insert($data);
	}
	
	public function updateUser(Application_Model_User $user)
	{
		$data = array(
				'firstName' => $user->getFirstName(),
				'lastName' => $user->getLastName(),
				'email' => $user->getEmail(),
				'password' =>md5($user->getPass()),
		);
		$this->update($data, 'id = '. (int)$user->getId());
	}
	
	public function deleteUser($id)
	{
		$id=(int)id;
		$this->delete('id =' . (int)$id);
	}
	
}