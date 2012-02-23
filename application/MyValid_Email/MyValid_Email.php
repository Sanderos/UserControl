<?php
namespace MyValid_Email;
use Zend_Validate_Abstract;

class MyValid_Email extends Zend_Validate_Abstract
{
	const FLOAT = 'float';
	protected $em;
	protected $_messageTemplates = array(
			self::FLOAT => "'%value%' email is not valid"
	);
	
	public function isValid($email)
	{
		$this->_setValue($email);
		$this->em = \Zend_Registry::get('em');
		if ($this->em->getRepository('entities\User')->getUserByEmail($email) != null) {
			$this->_error(self::FLOAT);
			return false;
		}
		return true;
	}
}