<?php
class Application_Form_Forgot extends Zend_Form
{
	public function init()
	{
		
		$validator = new Zend_Validate_Db_RecordExists(
				array(
						'table' => 'users',
						'field' => 'email'
				)
		);
		
		$this->setName('user');
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('email')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('EmailAddress')
		->addValidator('NotEmpty')
		->addValidator($validator);
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		
		$this->addElements(array($email, $submit));
	}
		
}