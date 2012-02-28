<?php
class Application_Form_Search extends Zend_Form
{
	public function init()
	{
		$this->setName('search');
		$searchfield = new Zend_Form_Element_Text('searchfield');
		$searchfield->setLabel('Search')
		->addFilter('StripTags')
		->addFilter('StringTrim');
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$this->addElements(array($searchfield, $submit));
	}
}