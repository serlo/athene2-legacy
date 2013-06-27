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
     * @var string
     */
    protected $route;

    /**
     *
     * @var string
     */
    protected $entityClass;

    /**
     *
     * @var string
     */
    protected $entityFactory;

    /**
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     *
     * @return string $route
     */
    public function getRoute ()
    {
        return $this->route;
    }

    /**
     *
     * @param string $route            
     * @return $this
     */
    public function setRoute ($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     *
     * @param string $entityClass            
     * @return $this
     */
    public function setEntityClass ($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     *
     * @param string $entityFactory            
     * @return $this
     */
    public function setEntityFactory ($entityFactory)
    {
        $this->entityFactory = $entityFactory;
        return $this;
    }

    /**
     *
     * @return EntityManagerInterface $_entityManager
     */
    public function getEntityManager ()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param EntityManagerInterface $_entityManager            
     * @return $this
     */
    public function setEntityManager (EntityManagerInterface $_entityManager)
    {
        $this->entityManager = $_entityManager;
        return $this;
    }

    public function getEntityFactory ()
    {
        return $this->entityFactory;
    }

    public function getEntityClass ()
    {
        return $this->entityClass;
    }

    public function indexAction ()
    {
        return $this->receiveAction();
    }
    
    /*
     * public function createAction () { $entity = $this->getEntityManager()->create($this->getEntityFactory()); $this->redirect()->toRoute(get_class($entity), array( 'action' => 'update', 'id' => $entity->getId() )); }
     */
    public function getSharedTaxonomyManager ()
    {
        return $this->getServiceLocator()->get('Taxonomy\SharedTaxonomyManager');
    }

    public function getObjectManager ()
    {
        return $this->getServiceLocator()->get('EntityManager');
    }

    public function createAction ()
    {
        $term = $this->params()->fromQuery('term');
        if (! $term)
            throw new \InvalidArgumentException();
        
        $entity = $this->getEntityManager()->create($this->getEntityFactory());
        $term = $this->getSharedTaxonomyManager()->getTerm($term);
        
        $term->addEntity($entity);
        
        $this->getObjectManager()->flush();
        
        $this->redirect()->toRoute($entity->getRoute(), array(
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