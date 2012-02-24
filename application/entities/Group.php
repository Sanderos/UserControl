<?php
namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Repositories\GroupRepository")
 * @Table(name="groups")
 **/
class Group
{
	// ...
	/**
	 * @ManyToMany(targetEntity="User", mappedBy="groups")
	 */
	private $users;
	
	public function __construct() {
		$this->users = new ArrayCollection();
	}
	
	/**
	 * @Id @GeneratedValue
	 * @Column(type="integer")
	 * @var int
	 **/
	protected $id;

	/**
	 * @Column(type="string")
	 * @var string
	 **/
	protected $name;
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getId() {
		return $this->id;
	}
	
	
	
	
	public function getUsers() {
		return $this->users;
	}
}