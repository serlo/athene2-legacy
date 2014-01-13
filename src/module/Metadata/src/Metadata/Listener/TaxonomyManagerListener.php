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

    public function onCreate(Event $e)
    {
        /* @var $term TaxonomyTermInterface */
        $term = $e->getParam('term');

        if ($term->hasParent()) {
            $parent = $term->getParent();
            $this->addMetadata($parent, $term);
        }
    }

    public function onUpdate(Event $e)
    {
        /* @var $term TaxonomyTermInterface */
        $term = $e->getParam('term');

        if ($term->hasParent()) {
            $parent = $term->getParent();
            $this->addMetadata($parent, $term);
        }
    }

    public function onAssociate(Event $e)
    {
        /* @var $term TaxonomyTermInterface */
        $term = $e->getParam('term');
        $object = $e->getParam('object');

        if ($object instanceof TaxonomyTermAwareInterface && $object instanceof UuidEntity) {
            $this->addMetadata($object, $term);
        }
    }

    public function onDissociate(Event $e)
    {
        /* @var $term TaxonomyTermInterface */
        $term = $e->getParam('term');
        $object = $e->getParam('object');

        if ($object instanceof TaxonomyTermAwareInterface && $object instanceof UuidEntity) {
            $this->removeMetadata($object, $term);
        }
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            $this->getMonitoredClass(),
            'create',
            array(
                $this,
                'onCreate'
            )
        );

        $this->listeners[] = $events->attach(
            $this->getMonitoredClass(),
            'update',
            array(
                $this,
                'onUpdate'
            )
        );

        $this->listeners[] = $events->attach(
            $this->getMonitoredClass(),
            'associate',
            array(
                $this,
                'onAssociate'
            )
        );

        $this->listeners[] = $events->attach(
            $this->getMonitoredClass(),
            'dissociate',
            array(
                $this,
                'onDissociate'
            )
        );
    }

    protected function getMonitoredClass()
    {
        return 'Taxonomy\Manager\TaxonomyManager';
    }

    protected function addMetadata(UuidEntity $object, TaxonomyTermInterface $term)
    {
        while ($term->hasParent()) {
            try {
                $this->getMetadataManager()->addMetadata(
                    $object->getUuidEntity(),
                    $term->getTaxonomy()->getName(),
                    $term->getName()
                );
            } catch (MetadataNotFoundException $e) {
            }
            $term = $term->getParent();
        }
    }

    protected function removeMetdata($object, TaxonomyTermInterface $term)
    {
        while ($term->hasParent()) {
            try {
                $metadata = $this->getMetadataManager()->findMetadataByObjectAndKeyAndValue(
                    $object->getUuidEntity(),
                    $term->getTaxonomy()->getName(),
                    $term->getName()
                );
                $this->getMetadataManager()->removeMetadata($metadata->getId());
            } catch (MetadataNotFoundException $e) {
            }
        }
    }
}
