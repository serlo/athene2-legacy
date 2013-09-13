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
namespace Entity\Provider\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Entity\EntityManagerInterface;
use Entity\EntityManagerAwareInterface;

abstract class AbstractController extends AbstractActionController implements EntityManagerAwareInterface {    
	/**
	 * @var EntityManagerInterface
	 */
	protected $entityManager;
	
    /**
     * @return EntityManagerInterface $_entityManager
     */
    public function getEntityManager ()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $_entityManager            
     * @return $this
     */
    public function setEntityManager (EntityManagerInterface $_entityManager)
    {
        $this->entityManager = $_entityManager;
        return $this;
    }

    protected function getEntity ($id = NULL)
    {
    	if (! $id){
    		$id = $this->getParam('entity');
    	}
    
    	$entity = $this->getEntityManager()->get($id);
    	
    	if(!$entity->providesComponent($this->getParam('provider')))
    		throw new \Exception('Entity does not know provider `' . $this->getParam('provider') . '`.');
    	
    	return $entity;
    }
}