<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Listener;

use Common\Listener\AbstractSharedListenerAggregate;
use Entity\Result\UrlResult;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;

class EntityControllerListener extends AbstractSharedListenerAggregate
{

    public function attachShared(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            $this->getMonitoredClass(),
            'create.postFlush',
            array(
                $this,
                'onCreate'
            ),
            -1000
        );
    }

    protected function getMonitoredClass()
    {
        return 'Entity\Controller\EntityController';
    }

    public function onCreate(Event $e)
    {
        /* var $entity \Entity\Entity\EntityInterface */
        $entity = $e->getParam('entity');

        $result = new UrlResult();
        $result->setResult(
            $e->getTarget()->url()->fromRoute(
                'entity/repository/add-revision',
                [
                    'entity' => $entity->getId()
                ]
            )
        );

        return $result;
    }
}
