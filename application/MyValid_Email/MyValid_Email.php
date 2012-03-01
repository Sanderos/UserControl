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
		$user = $this->em->getRepository('entities\User')->findOneByEmail($email);
		if ($user == null ) {
			return true;
		} else {
			if($user->getConfirm() == 1) {
				$this->em->remove($user);
				$this->em->flush();
				return true;
			}
			$this->_error(self::FLOAT);
			return false;
		}
	}	
		
}
