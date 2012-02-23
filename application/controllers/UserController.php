<?php
use Entities\User ;
use Entities\Group ;

class UserController extends Zend_Controller_Action
{
	protected $em;
	/**
	 * The default action - show the home page
	 */
    public function init() 
    {
    	if(!Zend_Auth::getInstance()->hasIdentity()) {
    		$this->_redirect('/index/login');
    	}
    	$user = new User();
    	$this->em = $this->getInvokeArg('bootstrap')->getResource('doctrine');
    }
    
    public function indexAction()
    {
    	$search =$this->_getParam('search');
    	$form = new Application_Form_Search();
    	$form->submit->setLabel('search');
    	$this->view->form = $form;
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)){
    			//$paginator = Zend_Paginator::factory($users->serachUsers($form->getValue('searchfield')));
    			$this->_helper->redirector('index', null , null, array('search'=>$form->getValue('searchfield')));
    		} else {
    			$paginator = Zend_Paginator::factory($this->em->getRepository('entities\User')->findAll());
    		}
    	} else {
    		//Initialize the Zend_Paginator
    		if($search != null) {
    			$paginator = Zend_Paginator::factory($this->em->getRepository('entities\User')->searchUsers($search));
    		}else {
    			$paginator = Zend_Paginator::factory($this->em->getRepository('entities\User')->findAll());
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
    			
    			$user = new User();
    			$user->setEmail($email);
    			$user->setFirstName($firstName);
    			$user->setLastName($lastName);
    			$user->setPass(md5($pass));
    			
    			$this->em->persist($user);
    			$this->em->flush();		
    			$this->_helper->redirector('index','user' , null, array());
    			 

    		} else {
    			$form->populate($formData);
    		}
    	}
    
    }
    
    //edit action
    public function editAction()
    {
    
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

    			$user =$this->em->getRepository('entities\User')->findOneById($id);
    			$user->setEmail($email);
    			$user->setFirstName($firstName);
    			$user->setLastName($lastName);
    			if($pass != '') {
    				$user->setPass(md5($pass));
    			}
    			$this->em->flush();
    			$this->_helper->redirector('index','user' , null, array());
    		} else {
    			$form->populate($formData);
    		}
    	}else {
    		$id = $this->_getParam('id', 0);
    		if ($id > 0) {
    			//$users = new Application_Model_DbTable_Users();
    			//$user =$users->getUser($id);
    			$user =$this->em->getRepository('entities\User')->findOneById($id);
    			$arr = array("id" => $user->getId(),
    					"firstName" => $user->getFirstName(),
    					"lastName" => $user->getLastName(),
    					"email" => $user->getEmail()
    			);
    			$form->populate($arr);
    
    		}else{
    			$this->_helper->redirector('index','user' , null, array());
    		}
    	}    	 
    }
    
    //delete action
    public function deleteAction()
    {
    	if ($this->getRequest()->isPost()) {
    		//if request is post
    		$del = $this->getRequest()->getPost('del');
    
    		//if delete button pressed (else go to index, overview)
    		if ($del == 'Yes') {
    			//get id
    			$id = $this->getRequest()->getPost('id');
    			//verwijder user
    			$user = $this->em->getRepository('entities\User')->findOneById($id);
    			$this->em->remove($user);
    			$this->em->flush();
    		}
    		$this->_helper->redirector('index','user' , null, array());;
    	} else {
    		$id = $this->_getParam('id', 0);
    		$user =$this->em->getRepository('entities\User')->findOneById($id);
    		if($user == null)$this->_helper->redirector('index','user' , null, array());
    		$this->view->user = $user;
    	}
    }
    //logout action
    public function logoutAction() {
    	// clear everything - session is cleared also!
    	Zend_Auth::getInstance()->clearIdentity();
    }
}
