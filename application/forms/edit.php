<?php


class Application_Form_Edit extends Zend_Form
{
	public function init()
	{
		$this->setName('user');
		
		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int');
		
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
		->addFilter('StringTrim');
	
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('email')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->addValidator('EmailAddress');
		
		$pass1 = new Zend_Form_Element_Password('pass1');
		$pass1->setLabel('Password')
		->addFilter('StripTags')
		->addFilter('StringTrim');
		
		$pass2 = new Zend_Form_Element_Password('pass2');
		$pass2->setLabel('Confirm password')
		->addValidator(new Zend_Validate_Identical('pass1'))
		->addFilter('StripTags')
		->addFilter('StringTrim');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$cansel = new Zend_Form_Element_Submit('cansel');
	
	
		$this->addElements(array($id, $firstName, $lastName, $email, $pass1 , $pass2, $submit));
	}
	}
	
	//EOF