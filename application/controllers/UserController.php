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
    	$searchT = $this->em->getRepository('Entities\User')->findAll();
    	$usersEmail = array();
    	foreach ($searchT as $user) {
    		array_push($usersEmail, $user->getEmail());
    	}
    	$search =$this->_getParam('search');
    	
    	$form = new Application_Form_Search(array('search'=>$usersEmail));
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
    	//$this->view->addHelperPath('../../library/ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
    	$this->view->paginator = $paginator;
    }
    
    //add action
    public function addAction()
    {
    	$groups = $this->em->getRepository('Entities\Group')->findAll();
    	$form = new Application_Form_Add(array('groups'=>$groups));
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
    			$group = $form->getValue('groups');
    			$user = new User();
    			$user->setEmail($email);
    			$user->setFirstName($firstName);
    			$user->setLastName($lastName);
    			$user->setPass(md5($pass));
    			$this->em->persist($user);
    			$this->em->flush();
				//get the user
    			//add user to group(s)
    			foreach ($group as $g) {
    				if($group!=''){
    					$user->addGroup($this->em->getRepository('Entities\Group')->findOneByName($g));
    				}
    				$this->em->flush();
    			}
    			$this->_helper->redirector('index','user' , null, array());
    		} else {
    			$form->populate($formData);
    		}
    	}
    }
    
    //add action
    public function addgroupAction()
    {
    	$form = new Application_Form_Addgroup();
    	$form->submit->setLabel('Add');
    	$this->view->form = $form;
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			//form valid add user
    			//get user information
    			$groupName = $form->getValue('name');
    			$group = new Group();
    			$group->setName($groupName);
    			if($this->em->getRepository('Entities\Group')->findOneByName($groupName) == null) {
    				$this->em->persist($group);
    				$this->em->flush();
	    		}
    			$this->_helper->redirector('groups','user' , null, array());
    		} else {
    			$form->populate($formData);
    		}
    	}
    
    }
    
    //edit action
    public function editAction()
    {
    	$id = $this->_getParam('id', 0);
    	$groups = $this->em->getRepository('Entities\Group')->findAll();
    	$user =$this->em->getRepository('entities\User')->findOneById($id);
    	$form = new Application_Form_Edit(array('groups'=>$groups ,'user'=>$user));
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
    			$group = $form->getValue('groups');
    			$user =$this->em->getRepository('entities\User')->findOneById($id);
    			$user->removeGroups();
    			$user->setEmail($email);
    			$user->setFirstName($firstName);
    			$user->setLastName($lastName);
    			if($pass != '') {
    				$user->setPass(md5($pass));
    			}
    			$this->em->flush();
	    		foreach ($group as $g) {
	    			if($group!=''){
	    				$user->addGroup($this->em->getRepository('Entities\Group')->findOneByName($g));
	    			}
	    			$this->em->flush();
	    		}
    			
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
    			$groups = $user->getGroup();
    			$group = '';
    			$arr = array("id" => $user->getId(),
    					"firstName" => $user->getFirstName(),
    					"lastName" => $user->getLastName(),
    					"email" => $user->getEmail()
    			);
    			$form->populate($arr);
    			$this->view->user = $user;
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
    
    public function deletegroupAction()
    {
    	if ($this->getRequest()->isPost()) {
    		//if request is post
    		$del = $this->getRequest()->getPost('del');
    		//if delete button pressed (else go to index, overview)
    		if ($del == 'Yes') {
    			//get id
    			$id = $this->getRequest()->getPost('id');
    			$group= $this->em->getRepository('Entities\Group')->findOneById($id);
    			$this->em->remove($group);
    			$this->em->flush();
    		}
    		$this->_helper->redirector('groups','user' , null, array());;
    	} else {
    		$id = $this->_getParam('id', 0);
    		$group= $this->em->getRepository('Entities\Group')->findOneById($id);
    		if($group == null)$this->_helper->redirector('groups','user' , null, array());
    		$this->view->group = $group;
    	}
    }
    
    //logout action
    public function logoutAction() {
    	// clear everything - session is cleared also!
    	Zend_Auth::getInstance()->clearIdentity();
    }
    
    //edit groups
    public function groupsAction() {
    	$paginator = Zend_Paginator::factory($this->em->getRepository('Entities\Group')->findAll());
    	$currentPage= $this->_getParam('page');
    	//Set the properties for the pagination
    	$paginator->setItemCountPerPage(5);
    	$paginator->setPageRange(3);
    	$paginator->setCurrentPageNumber($currentPage);
    	$this->view->paginator = $paginator;
    }
    
    public function editgroupAction() {
    	$id = $this->_getParam('id', 0);
    	$group =$this->em->getRepository('Entities\Group')->findOneById($id);
    	$users = $this->em->getRepository('Entities\User')->findAll();
    	$form = new Application_Form_Editgroup(array('user'=>$users, 'group' => $group));
    	$form->submit->setLabel('Edit group');
    	$this->view->form = $form;
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			//edit group
    			$id = $form->getValue('id');
    			$name = $form->getValue('name');
    			$checkusers = $form->getValue('users');
    			$group =$this->em->getRepository('Entities\Group')->findOneById($id);
    			$group->setName($name);
    			foreach($group->getUsers() as $u) {
    				$u->removeUser($group);
    			}
    			foreach ($checkusers as $u) {
    				if($u!=''){
    					$user = $this->em->getRepository('Entities\User')->findOneByEmail($u);
    					$user->addGroup($group);
    				}
    				$this->em->flush();
    			}
    			$this->em->persist($group);
    			$this->em->flush();
    			$this->_helper->redirector('groups','user' , null, array());		
    		}else {
    			$form->populate($formData);
    		}
    	}else {
    	$id = $this->_getParam('id', 0);
    		$group =$this->em->getRepository('Entities\Group')->findOneById($id);
    		if ($group != null) {
    			$arr = array("id" => $group->getId(),
    					"name" => $group->getName()	
    			);
    			$form->populate($arr);
    			$this->view->users = $group->getUsers();
    		}else{
    			$this->_helper->redirector('groups','user' , null, array());
    		}
    	}
    }
    
    public function deleteuserfromgroupAction() {
    	$id_user= $this->_getParam('userid');
    	$id= $this->_getParam('id');
    	$group =$this->em->getRepository('Entities\Group')->findOneById($id);
    	$user =$this->em->getRepository('Entities\User')->findOneById($id_user);
    	$user->removeUser($group);
    	$this->em->flush();
    	$this->_helper->redirector('editgroup','user' , null, array('id'=>$id));
    }
    
}
