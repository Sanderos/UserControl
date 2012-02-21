<?php
class Application_Form_Login extends Zend_Form
{

	public function init()
	{
		$this->setName('user');
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('email')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('EmailAddress')
		->addValidator('NotEmpty');
		
		$pass = new Zend_Form_Element_Password('pass');
		$pass->setLabel('Password')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		
		$this->addElements(array($email, $pass, $submit));
	}
}