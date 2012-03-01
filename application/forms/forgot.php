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
		
		$recaptchaKeys = Zend_Registry::get('config.recaptcha');
		
		$recaptcha = new Zend_Service_ReCaptcha($recaptchaKeys['pubkey'], $recaptchaKeys['privkey'],
				NULL, array('theme' => 'clean'));
		
		$captcha = new Zend_Form_Element_Captcha('captcha',
				array(
						'label' => 'Captcha',
						'captcha' =>  'ReCaptcha',
						'captchaOptions'        => array(
								'captcha'   => 'ReCaptcha',
								'service' => $recaptcha
						)
				)
		);
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$this->addElements(array($email,$captcha, $submit));
	}
		
}