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
namespace Entity\Plugin\Repository\Listener;

use Entity\Plugin\Listener\AbstractListener;
use Entity\Result\UrlResult;
use Zend\EventManager\Event;

class Repository extends AbstractListener
{
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\SharedListenerAggregateInterface::attachShared()
     */
    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('Entity\Controller\EntityController', 'create.postFlush', function (Event $e)
        {
            /* var $entity \Entity\Service\EntityServiceInterface */
            $entity = $e->getParam('entity');
            $data = $e->getParam('data');
            
            if ($entity->hasPlugin('repository')) {
                $result = new UrlResult();
                $result->setResult($entity->plugin('repository')
                    ->getRouter()
                    ->assemble(array(
                    'entity' => $entity->getId(),
                ), array(
                    'name' => 'entity/plugin/repository/add-revision'
                )));
                return $result;
            }
        }, 2);
    }
}