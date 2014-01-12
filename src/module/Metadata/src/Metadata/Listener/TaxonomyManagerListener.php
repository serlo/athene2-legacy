<?php
/**
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author        Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license        LGPL-3.0
 * @license        http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright    Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Metadata\Listener;

use Metadata\Exception\MetadataNotFoundException;
use Taxonomy\Entity\TaxonomyTermAwareInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Uuid\Entity\UuidEntity;
use Zend\EventManager\Event;

class TaxonomyManagerListener extends AbstractListener
{
    public function onAssociate(Event $e)
    {
        /* @var $term TaxonomyTermInterface */
        $term = $e->getParam('term');
        $object = $e->getParam('object');

        if ($object instanceof TaxonomyTermAwareInterface && $object instanceof UuidEntity) {
            while ($term->hasParent()) {
                $this->getMetadataManager()->addMetadata($object->getUuidEntity(), $term->getTaxonomy()->getName(), $term->getName());
                $term = $term->getParent();
            }
        }
    }

    public function onDissociate(Event $e)
    {
        /* @var $term TaxonomyTermInterface */
        $term = $e->getParam('term');
        $object = $e->getParam('object');

        if ($object instanceof TaxonomyTermAwareInterface && $object instanceof UuidEntity) {
            while ($term->hasParent()) {
                try {
                    $metadata = $this->getMetadataManager()->findMetadataByObjectAndKeyAndValue($object->getUuidEntity(), $term->getTaxonomy()->getName(), $term->getName());
                    $this->getMetadataManager()->removeMetadata($metadata->getId());
                } catch (MetadataNotFoundException $e) {}
            }
        }
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'associate', array(
            $this,
            'onAssociate'
        ));

        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'dissociate', array(
            $this,
            'onDissociate'
        ));
    }

    protected function getMonitoredClass()
    {
        return 'Taxonomy\Manager\TaxonomyManager';
    }
}