<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	function _initDoctrine() 
	{
		$lib = '../library/doctrine/lib/';
		require $lib . 'vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';
		
		$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', $lib . 'vendor/doctrine-common/lib');
		$classLoader->register();
		
		$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', $lib . 'vendor/doctrine-dbal/lib');
		$classLoader->register();
		
		$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', $lib);
		$classLoader->register();
		
	}

}

