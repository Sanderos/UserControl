<?php
use Entities\User ;
use Entities\Group ;

class IndexController extends Zend_Controller_Action
{
	
	protected $em;
    public function init()
    {
        /* Initialize action controller here */
    	$user = new User();
    	$this->em = $this->getInvokeArg('bootstrap')->getResource('doctrine');
    }
    
    //edit action
    public function forgotAction()
    {
    	$form = new Application_Form_Forgot();
    	$form->submit->setLabel('send email');
    	$this->view->form = $form;
    	
	    if ($this->getRequest()->isPost()) {
	   		//get formdata
	    	$formData = $this->getRequest()->getPost();
	    	if ($form->isValid($formData)) {
	    		$email = $form->getValue('email');
	    		//check mail
	    		//create unique code
	    		$unique = substr(md5(uniqid()), 3, 6);				
	   			$mail = new Zend_Mail();
	    		$tr = new Zend_Mail_Transport_Smtp('uit.telenet.be');
	    		$mail->setFrom('forgot@marlon.be', 'Server');
	   			$mail->addTo($email);
	    		$mail->setSubject('Password recovery');
	   			$mail->setBodyText('your new  pasword is ' . $unique);
	    		$mail->send($tr);
	    		//$users->changePass($email, $unique);
	    		$user = $this->em->getRepository('entities\User')->findOneByEmail($email);
	    		$user->setPass(md5($unique));
    		}
    	}
        
    }
    
    //login action
    public function loginAction() {
    	$form = new Application_Form_Login();
    	$form->submit->setLabel('Login');
    	$this->view->form = $form;
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			//form valid add user get user information
    			$email = $form->getValue('email');
    			$pass = $form->getValue('pass');
    			$user = $this->em->getRepository('entities\User')->login($email, md5($pass));
    			if($user != null){
    				$result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,$user,array());
    			}else{
    				$result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,null,array());
    			}
    			$auth = Zend_Auth::getInstance();
    			if($result->isValid())
    			{
    				$authStorage = $auth->getStorage();
    				$authStorage->write($user);
    				$this->_helper->redirector('index','user' , null, array());
    			} else {
    				//do nothing 
    				$this->view->error = "username and/or password are incorrect";
    			}
    		} else {
    			$form->populate($formData);
    		}
    	}
    }
}

