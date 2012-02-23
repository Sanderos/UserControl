<?php 
#add a group
		$groep = new Group();
    	$groep->setName('test');
    	$this->em->persist($groep);
    	$this->em->flush();
    	
 #add a user to a group
    	$group = $this->em->getRepository('Entities\Group')->findOneByName('test');
    	$user = $this->em->getRepository('Entities\User')->findOneById(2);
    	$user->addGroup($group);
    	$this->em->flush();
    	
#groups
    	$user = $this->em->getRepository('Entities\User')->findOneById(2);
    	$group = $user->getGroup();
    	Zend_Debug::dump($group);