<?php
use Doctrine\ORM\EntityManager,
Doctrine\ORM\Configuration;

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
		$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
		$classLoader->register();
		$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
		$classLoader->register();
		$classLoader = new \Doctrine\Common\ClassLoader('Repositories', __DIR__);
		$classLoader->register();
		$classLoader = new \Doctrine\Common\ClassLoader('MyValid_Email', __DIR__);
		$classLoader->register();
		$doctrineConfig = $this->getOption('doctrine');
		//$cache = new \Doctrine\Common\Cache\ApcCache;
		// apc fix by sander
		$cache = new \Doctrine\Common\Cache\ArrayCache;
		$config = new Configuration;
		$driverImpl = $config->newDefaultAnnotationDriver($doctrineConfig['entityfolder']);
		$config->setMetadataDriverImpl($driverImpl);
		$config->setQueryCacheImpl($cache);
		$config->setProxyDir('/proxies');
		$config->setProxyNamespace('Proxies');
		$config->setAutoGenerateProxyClasses(false);
		$doctrineDB = $doctrineConfig['db'];
		$connectionOptions = array(
				'driver' => $doctrineDB['driver'],
				'host'     => $doctrineDB['host'],
			    'user' => $doctrineDB['user'],
			    'password' => $doctrineDB['password'],
			    'dbname'   => $doctrineDB['dbname']
		);
		$em = EntityManager::create($connectionOptions, $config);
		
		$view = new Zend_View();
		$view->addHelperPath('../library/ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
		$viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
		
		Zend_Registry::set('em', $em);
		return $em;
	}
}

