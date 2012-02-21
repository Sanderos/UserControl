<?php

class IndexController extends Zend_Controller_Action
{
	
	
    public function init()
    {
        /* Initialize action controller here */
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
    			$users = new Application_Model_DbTable_Users();
    			if($users->checkEmail($email)){
    				//create unique code
    				$unique = substr(md5(uniqid()), 3, 6);
    				
    				$mail = new Zend_Mail();
    				$tr = new Zend_Mail_Transport_Smtp('uit.telenet.be');
    				$mail->setFrom('forgot@marlon.be', 'Server');
    				$mail->addTo($email);
    				$mail->setSubject('Password recovery');
    				$mail->setBodyText('your new  pasword is ' . $unique);
    				$mail->send($tr);
    				$users->changePass($email, $unique);
    				$this->view->mail ="Email send";
    				
    			}else {
    				$this->view->error ="User not found";
    				$form->populate($formData);
    			}
    			
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
    			 
    			//form valid add user
    			//get user information
    			
    			$email = $form->getValue('email');
    			$pass = $form->getValue('pass');
    			
    			$dbAdapter = new Application_Model_DbTable_Users();
    			$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter->getAdapter());
    			$authAdapter->setTableName('users')
    			->setIdentityColumn('email')
    			->setCredentialColumn('password');
    			 
    			$authAdapter->setIdentity($email)
    			->setCredential(md5($pass));
    			
    			$auth = Zend_Auth::getInstance();
    			$result = $auth->authenticate($authAdapter);
    			
    			if($result->isValid())
    			{
    				// get all info about this user from the login table
    				// ommit only the password, we don't need that
    				$userInfo = $authAdapter->getResultRowObject(null, 'password');
    			
    				// the default storage is a session with namespace Zend_Auth
    				$authStorage = $auth->getStorage();
    				$authStorage->write($userInfo);
    			
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

