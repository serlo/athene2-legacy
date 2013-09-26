<?php
namespace Page\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
{

	public function createService (ServiceLocatorInterface $serviceLocator)
	{
		$ctr = new IndexController();

		$ctr->setPageService($serviceLocator->getServiceLocator()
				->get('Page\Service\PageService'));
		return $ctr;
	}
}
