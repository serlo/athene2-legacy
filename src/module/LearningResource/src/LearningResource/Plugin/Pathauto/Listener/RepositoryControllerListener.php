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
namespace LearningResource\Plugin\Pathauto\Listener;

use Entity\Plugin\Listener\AbstractListener;
use Zend\EventManager\Event;

class RepositoryControllerListener extends AbstractListener
{
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\SharedListenerAggregateInterface::attachShared()
     */
    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('LearningResource\Plugin\Repository\Controller\RepositoryController', 'add-revision', function (Event $e)
        {
            /* var $entity \Entity\Service\EntityServiceInterface */
            $entity = $e->getParam('entity');
            $data = $e->getParam('post');
            /* var $entityManager \Entity\Manager\EntityManagerInterface */
            $entityManager = $entity->getEntityManager();
            if($entity->hasPlugin('pathauto')){
                $entity->plugin('pathauto')->inject();
            }
        }, 2);
    }
}