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
namespace Entity\Plugin\LearningResource\Listener;

use Zend\EventManager\Event;
use Taxonomy\Exception\TermNotFoundException;
use Common\Listener\AbstractSharedListenerAggregate;
use Entity\Plugin\LearningResource\Exception\UnstatisfiedDependencyException;

class EntityControllerListener extends AbstractSharedListenerAggregate
{
    use\Metadata\Manager\MetadataManagerAwareTrait;

    public function onCreate(Event $e)
    {
        /* @var $term \Entity\Service\EntityServiceInterface */
        $entity = $e->getParam('entity');
        /* @var $term \Taxonomy\Service\TermServiceInterface */
        $term = $e->getParam('term');
        
        $object = $entity->getEntity()->getUuidEntity();
        
        $subject = $entity->learningResource()->getDefaultSubject();
        $this->getMetadataManager()->addMetadata($object, 'subject', $subject->getName());
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'create', array(
            $this,
            'onCreate'
        ), -100);
    }

    protected function getMonitoredClass()
    {
        return 'Entity\Controller\EntityController';
    }
}