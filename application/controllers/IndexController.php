<?php

class IndexController extends Zend_Controller_Action
{
	
	
    public function init()
    {
    	
        /* Initialize action controller here */
    	
    	
    	
    	
    }
    
    public function checkLogin()
    {
    	if(!Zend_Auth::getInstance()->hasIdentity()) {
    		$this->_redirect('/index/login');
    	}
    }
	
    //index action
    public function indexAction()
    {
    	$this->checkLogin();
    	
    	
    	$search =$this->_getParam('search');
    	
    	$users = new Application_Model_DbTable_Users();
    	
    	$form = new Application_Form_Search();
    	$form->submit->setLabel('search');
    	$this->view->form = $form;
    	
    	
    	
    	
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		
    		if ($form->isValid($formData)){
    			
    			//$paginator = Zend_Paginator::factory($users->serachUsers($form->getValue('searchfield')));
    			$this->_helper->redirector('index', null , null, array('search'=>$form->getValue('searchfield')));
    			
    			
    		} else {

    			$paginator = Zend_Paginator::factory($users->getUsers());
	
    		}
    	} else {
    		//Initialize the Zend_Paginator
    		
    		
    	if($search != null) {
    		$paginator = Zend_Paginator::factory($users->serachUsers($search));
    	}else {
    		$paginator = Zend_Paginator::factory($users->getUsers());
    	}
    			
    		
    	}
    	
    	$currentPage= $this->_getParam('page');
    	
    	//Set the properties for the pagination
    	$paginator->setItemCountPerPage(5);
    	
    	$paginator->setPageRange(3);
    	$paginator->setCurrentPageNumber($currentPage);
    	
		
    	$this->view->paginator = $paginator;
    }
    
    //add action
    public function addAction()
    {
    	$this->checkLogin();
    	$form = new Application_Form_Add();
    	$form->submit->setLabel('Add');
    	$this->view->form = $form;
    
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			
    			//form valid add user
    			//get user information
    			$firstName = $form->getValue('firstName');
    			$lastName = $form->getValue('lastName');
    			$email = $form->getValue('email');
    			$pass = $form->getValue('pass1');
    			
    			//make user
    			$users = new Application_Model_DbTable_Users();
    			
    			
    			if($users->checkEmail($email)){
    				$this->view->error ="The email allready exists in our database";
    				$form->populate($formData);
    			}else {
	    			//add user to database
	    			$users->addUser(new Application_Model_User(null, $firstName, $lastName, $email, $pass));
	    			
	    			$this->_helper->redirector('index');
    			}
    			
    		} else {
    			$form->populate($formData);
    		}
    	}
    
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
    
    //edit action
    public function editAction()
    {
    	$this->checkLogin();
    	$form = new Application_Form_Edit();
    	$form->submit->setLabel('Edit');
    	$this->view->form = $form;
    	
    	
    	
    	if ($this->getRequest()->isPost()) {
    		//get formdata
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			$id = $form->getValue('id');
    			
    			$firstName = $form->getValue('firstName');
    			$lastName = $form->getValue('lastName');
    			$email = $form->getValue('email');
    			$pass = $form->getValue('pass1');
    			
    			
    			
    			 
    			//edit user
    			$users = new Application_Model_DbTable_Users();
    			$user = $users->getUser($id);
    			$user->setEmail($email);
    			$user->setFirstName($firstName);
    			$user->setLastName($lastName);
    			if($pass != '') {
    				$user->setPass($pass);
    			}
    			
    			
    				//add user to database
    			$users->updateUser($user);
    			$this->_helper->redirector('index');
    			
    			
    		} else {
    			$form->populate($formData);
    		}
    	}else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $users = new Application_Model_DbTable_Users();
                $user =$users->getUser($id);
                
                $arr = array("id" => $user->getId(), 
                		"firstName" => $user->getFirstName(),
                		"lastName" => $user->getLastName(),
                		"email" => $user->getEmail()
                		);
                $form->populate($arr);
                
            }else{
            	$this->_helper->redirector('index');
            }
        }
    	
    	
    	
    }
    
    public function logoutAction() {
    	
    	
    	// clear everything - session is cleared also!
    	Zend_Auth::getInstance()->clearIdentity();
    	
    	
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
    			->setCredential($pass);
    			
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
    			
    				$this->_helper->redirector('index');
    		
    			} else {
    				//do nothing 
    				
    				$this->view->error = "username and/or password are incorrect";
    			}
    			
    			 
    		} else {
    			$form->populate($formData);
    		}
    	}
    	
    	
    }
    
    //delete action
    public function deleteAction()
    {
    	$this->checkLogin();
    	if ($this->getRequest()->isPost()) {
    		//if request is post
    		$del = $this->getRequest()->getPost('del');
    		
    		//if delete button pressed (else go to index, overview)
    		if ($del == 'Yes') {
    			//get id
    			$id = $this->getRequest()->getPost('id');
    			
    			//verwijder user
    			$users = new Application_Model_DbTable_Users();
    			$users->deleteUser($id);
    		}
    		$this->_helper->redirector('index');
    	} else {
    		
    		$id = $this->_getParam('id', 0);
    		
    		
    		$users = new Application_Model_DbTable_Users();
    		if($users->getUser($id) == null) $this->_helper->redirector('index');
    		$this->view->user = $users->getUser($id);
    		
    		
    	}
    }


}

