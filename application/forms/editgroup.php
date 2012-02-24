<?php
class Application_Form_Editgroup extends Zend_Form
{
	

	private $us;
	private $group;
	
	public function setUser($user) {
		$this->us = $user;
	}
	
	public function setGroup($group) {
		$this->group = $group;
	}
	
	public function init(){
		$this->setName('group');
		
		
		
		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int');
		
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('name')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$users = new Zend_Form_Element_Multiselect ('users');
		$users->setLabel('Users');
		
		foreach ($this->us as $u) {
			$users->addMultiOption($u->getEmail(),$u->getEmail());	
		}
		$gr = array();
		foreach($this->group->getUsers() as $g){
			array_push($gr, $g->getEmail());
		}
		$users->setValue($gr);
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$this->addElements(array($id,$name,$users,$submit));
	}

}