<?php
class Application_Form_Search extends Zend_Form
{
	private $search;
	
	public function setSearch($search) {
		$this->search = $search;
	}
	
	public function init()
	{
		$this->setName('search');
		$searchfield = new ZendX_JQuery_Form_Element_AutoComplete('searchfield');
		$searchfield->setLabel('Search');
		$searchfield->setJQueryParams(array('source'=>$this->search));
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$this->addElements(array($searchfield, $submit));
	}
}