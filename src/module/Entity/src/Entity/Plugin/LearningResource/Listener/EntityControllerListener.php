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

    public function onAddToTerm(Event $e)
    {
        /* @var $term \Entity\Service\EntityServiceInterface */
        $entity = $e->getParam('entity');
        /* @var $term \Taxonomy\Service\TermServiceInterface */
        $term = $e->getParam('term');
        
        try {
            $entity->learningResource()->isStatisfied();
            
            $object = $entity->getEntity()->getUuidEntity();
            
            try {
                $subject = $term->findAncestorByType('subject');
                $this->getMetadataManager()->addMetadata($object, 'subject', $subject->getName());
            } catch (TermNotFoundException $e) {}
        } catch (UnstatisfiedDependencyException $e) {
            return false;
        }
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'addToTerm', array(
            $this,
            'onAddToTerm'
        ));
    }

    protected function getMonitoredClass()
    {
        return 'Entity\Controller\EntityController';
    }
}