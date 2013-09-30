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
use Zend\EventManager\ResponseCollection;
use Entity\Result;

class EntityController extends AbstractActionController
{
    use\Entity\Manager\EntityManagerAwareTrait;

    public function createAction()
    {
        $type = $this->params('type');
        $entity = $this->getEntityManager()->createEntity($type, $this->params()
            ->fromQuery());
        
        $results = $this->getEventManager()->trigger('createEntity.preFlush', $this, array(
            'entity' => $entity,
            'data' => $this->params()
                ->fromQuery()
        ));
        
        //$this->getEntityManager()
         //   ->getObjectManager()
         //   ->flush($entity);
        
        $response = $this->getEntityManager()
            ->getEventManager()
            ->trigger('createEntity.postFlush', $this, array(
            'entity' => $entity,
                
            'data' => $this->params()
                ->fromQuery()
        ));
            
        die("controller");
        
        $this->checkResponse($response);
        
        $this->redirect()->toReferer();
    }

    public function checkResponse(ResponseCollection $response)
    {
        foreach ($response as $result) {
            if ($result instanceof Result\UrlResult) {
                $this->redirect()->toUrl($result->getResult());
            }
        }
    }
}