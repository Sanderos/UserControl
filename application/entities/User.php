<?php
/**
 * @Entity @Table(name="users")
**/
class User
{
	/**
	 * @Id @GeneratedValue @Column(type="integer")
	 * @var int
	 **/
	protected $id;

	/**
	 * @Column(type="string")
	 * @var string
	 **/
	protected $firstName;
	
	/**
	 * @Column(type="string")
	 * @var string
	 **/
	protected $lastName;
	
	/**
	 * @Column(type="string")
	 * @var string
	 **/
	protected $email;
	
	/**
	 * @Column(type="string")
	 * @var string
	 **/
	protected $password;

	// .. (other code)
	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}
	
	public function getLastName() {
		return $this->lastName;
	}
	
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}
	
	public function getFirstName() {
		return $this->firstName;
	}
	
	public function setPass($pass) {
		$this->pass = $pass;
	}
	
	public function getPass() {
		return $this->pass;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function getId() {
		return $this->id;
	}
}
