<?php
class Application_Model_User
{
	protected $_lastName;
	protected $_firstName;
	protected $_email;
	protected $_id;
	protected $_pass;

	public function application_Model_User($id, $firstName, $lastName , $email, $pass) {
		$this->_lastName = $lastName;
		$this->_firstName = $firstName;
		$this->_pass = $pass;
		$this->_email = $email;
		$this->_id = $id;
	}

	public function setLastName($lastName) {
		$this->_lastName = $lastName;
	}
	public function getLastName() {
		return $this->_lastName;
	}
	
	public function setFirstName($firstName) {
		$this->_firstName = $firstName;
	}
	
	public function getFirstName() {
		return $this->_firstName;
	}
	
	public function setPass($pass) {
		$this->_pass = $pass;
	}
	
	public function getPass() {
		return $this->_pass;
	}

	public function setEmail($email) {
		$this->_email = $email;	
	}
	
	public function getEmail() {
		return $this->_email;
	}
	
	public function getId() {
		return $this->_id;
	}
	
	
}