<?php
use Entities\User ;
use Entities\Group ;

class Application_Form_Add extends Zend_Form
{
	private $groups;
	
	public function setGroups($groups) {
		$this->groups = $groups;
	}
	
	public function init()
	{
		$this->setName('user');

		$firstName = new Zend_Form_Element_Text('firstName');
		$firstName->setLabel('First name')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');

		$lastName = new Zend_Form_Element_Text('lastName');
		$lastName->setLabel('Last name')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		//Check that the email address exists in the database
		$validator = new MyValid_Email\MyValid_Email();
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('email')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('EmailAddress')
		->addValidator('NotEmpty')
		->addValidator($validator);
		
		$pass1 = new Zend_Form_Element_Password('pass1');
		$pass1->setLabel('Password')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$pass2 = new Zend_Form_Element_Password('pass2');
		$pass2->setLabel('Confirm password')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator(new Zend_Validate_Identical('pass1'))
		->addValidator('NotEmpty');
		
		$group = new Zend_Form_Element_MultiCheckbox('groups');
		$group->setLabel('groups');
		foreach ($this->groups as $g) {
			$group->addMultiOption($g->getName(),$g->getName());
		}
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$this->addElements(array($firstName, $lastName, $email, $pass1 , $pass2,$group, $submit));
	}
}

//EOF