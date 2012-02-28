<?php

class Application_Form_Addgroup extends Zend_Form
{
	public function init()
	{
		$this->setName('group');

		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('name')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty');
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$this->addElements(array($name,$submit));
	}
}

//EOF