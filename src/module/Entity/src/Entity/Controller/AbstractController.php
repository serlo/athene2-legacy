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
use Entity\EntityManagerInterface;

abstract class AbstractController extends AbstractActionController
{

    /**
     *
     * @var EntityManagerInterface
     */
    protected $_entityManager;

    /**
     *
     * @return EntityManagerInterface $_entityManager
     */
    public function getEntityManager ()
    {
        return $this->_entityManager;
    }

    /**
     *
     * @param EntityManagerInterface $_entityManager            
     * @return $this
     */
    public function setEntityManager (EntityManagerInterface $_entityManager)
    {
        $this->_entityManager = $_entityManager;
        return $this;
    }

    protected abstract function getEntityFactory ();

    protected abstract function getEntityClass ();

    public function indexAction ()
    {
        return $this->receiveAction();
    }

    public function createAction ()
    {
        $entity = $this->getEntityManager()->create($this->getEntityFactory());
        $this->redirect()->toRoute(get_class($entity), array(
            'action' => 'update',
            'id' => $entity->getId()
        ));
    }

    public function deleteAction ()
    {
        $entity = $this->getEntity();
        $entity->getManager()->delete($entity);
        
        $this->flashMessenger()->addSuccessMessage('Löschung erfolgreich!');
        $ref = $this->getRequest()
            ->getHeader('Referer')
            ->getUri();
        $ref = $ref ? $ref : '/';
        $this->redirect()->toUrl($ref);
        
        return null;
    }

    public function purgeAction ()
    {
        throw new \Exception('Not implemented');
        // TODO
    }

    protected function getEntity ()
    {
        $id = $this->getParam('id');
        $entity = $this->getEntityManager()->get($id);
        $controllerName = $this->params('controller');
        $actionName = $this->params('action');
        
        if (get_class($entity) != $this->getEntityClass()) {
            // $this->redirect()->toRoute(get_class($entity),array('action' => $actionName, 'id' => $entity->getId()));
            throw new \Exception('This controller can\'t handle the requested entity.');
        }
        
        return $entity;
    }
}