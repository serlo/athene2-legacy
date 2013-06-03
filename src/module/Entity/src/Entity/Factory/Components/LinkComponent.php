<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Factory\Components;

use Core\Entity\EntityInterface;
use Doctrine\Common\Collections\Collection;
use Link\LinkManagerInterface;
use Entity\Service\EntityServiceInterface;
use Core\Component\ComponentInterface;
use Core\Component\AbstractComponent;

class LinkComponent extends AbstractComponent implements ComponentInterface {
    
    /**
     * @var LinkManagerInterface
     */
    protected $linkManager;
    
    /**
     * @return \Link\LinkManagerInterface $linkManager
     */
    public function getLinkManager ()
    {
        return $this->linkManager;
    }

	/**
     * @param \Link\LinkManagerInterface $linkManager
     * @return $this
     */
    public function setLinkManager (\Link\LinkManagerInterface $linkManager)
    {
        $this->linkManager = $linkManager;
        return $this;
    }

	public function __construct (EntityServiceInterface $entityService){
	    $this->setLinkManager($entityService->getLinkManager());
		$linkManager = $this->getLinkManager();
		$linkService = $linkManager->create($entityService->getEntity());
    }
	
	public function getChildren(){
		$linkService = $this->getComponent('link');
		return $this->_buildFromCollection($linkService->getChildren());
	}

	public function getParents(){
		$linkService = $this->getComponent('link');
		return $this->_buildFromCollection($linkService->getParents());
	}
	
	public function findChildren($factoryClassName){
		$linkService = $this->getComponent('link');
		return $this->_findByFactoryClassName($linkService->getChildren(), $factoryClassName);
	}

	public function findParents($factoryClassName){
		$linkService = $this->getComponent('link');
		return $this->_findByFactoryClassName($linkService->getParents(), $factoryClassName);
	}

	public function findParent($factoryClassName){
		$linkService = $this->getComponent('link');
		return current($this->_findByFactoryClassName($linkService->getParents(), $factoryClassName));
	}
	
	public function findChild($factoryClassName){
		$linkService = $this->getComponent('link');
		return current($this->_findByFactoryClassName($linkService->getChildren(), $factoryClassName));
	}
	
	protected function _findByFactoryClassName(Collection $collection, $factoryClassName){
		$results = array();
		$currentDepth = 1;
		$collection->first();
		foreach($collection->toArray() as $entity){
		    if($entity->get('factory')->get('className') == $factoryClassName ){
			    $results[] = $this->_factory($entity);
		    }
		}
		return $results;
	}
	
	protected function _buildFromCollection(Collection $collection){
		$results = array();
		while(!$collection->isEmpty()){
			$results[] = $this->_factory($collection->current());
			$collection->next();
		}
		return $results;
	}
	
	protected function _factory(EntityInterface $entity){
		return $this->getManager()->get($entity);
	}
}