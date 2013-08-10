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
namespace Entity\Controller;

use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractController extends AbstractActionController
{	
	public function __construct(){
		throw new \Exception('Deprecated');
	}

    protected function getEntity ($id = NULL)
    {
        if (! $id)
            $id = $this->getParam('id');
        
        $entity = $this->getEntityManager()->get($id);
        //$entity->setController($this);
        
        if (get_class($entity) != $this->getEntityClass())
            throw new \Exception('This controller can\'t handle the requested entity.');
            // $this->redirect()->toRoute(get_class($entity),array('action' => $actionName, 'id' => $entity->getId()));
        
        return $entity;
    }
}