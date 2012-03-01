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
	    		$user = $this->em->getRepository('entities\User')->findOneByEmail($email);
	    		if($user->getConfirm() == 0 ) {
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

		    		$user->setPass(md5($unique));
	    		} else {
	    			$this->view->error = "Your email hasn't been verified yet";
	    		}
	    		
    		}
    	}
        
    }
    
    //login action
    public function loginAction() 
    {
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
    
    //registreer action
    public function registerAction() 
    {
    	$form = new Application_Form_Register();
    	$form->submit->setLabel('register');
    	$this->view->form = $form;
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			$firstName = $form->getValue('firstName');
    			$lastName = $form->getValue('lastName');
    			$email = $form->getValue('email');
    			$pass = $form->getValue('pass1');
    			$user = new User();
    			$user->setEmail($email);
    			$user->setFirstName($firstName);
    			$user->setLastName($lastName);
    			$user->setPass(md5($pass));
    			$user->setConfirm(1);
    			$this->em->persist($user);
    			$this->em->flush();
    			$mail = new Zend_Mail();
    			$tr = new Zend_Mail_Transport_Smtp('uit.telenet.be');
    			$mail->setFrom('forgot@marlon.be', 'Server');
    			$mail->addTo($email);
    			$mail->setSubject('confirm your email');
    			$mail->setBodyText('http://localhost/beheersysteem/public/index/confirm/code/'.time().$user->getId());
    			$mail->send($tr);
    			$this->_helper->redirector('login','index' , null, array());
    		}
    	}
    }
    
    //confirm action
    public function confirmAction() {
    	$code= substr($this->_getParam('code'), 10 ,strlen($this->_getParam('code'))-10);
    	
    	if($code == null ) $this->view->error = "No code found";
    	$user =$this->em->getRepository('entities\User')->findOneById($code);
    	if($user != null && $user->getConfirm() == 1) {
    		$this->view->valid = true;
    		$user->setConfirm(0);
    		$this->em->persist($user);
    		$this->em->flush();
    	} else {
    		$this->view->error = "Code not vallid";
    	}
    	
    }
}

