<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Normalizer\Adapter;

use Entity\Entity\EntityInterface;

class EntityAdapter extends AbstractAdapter
{
    /**
     * @return EntityInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    public function isValid($object)
    {
        return $object instanceof EntityInterface;
    }

    protected function getContent()
    {
        return $this->getField('content');
    }

    protected function getCreationDate()
    {
        return $this->getObject()->getTimestamp();
    }

    protected function getField($field)
    {
        $entity = $this->getObject();
        $id     = $entity->getId();

        if (is_array($field)) {
            $fields = $field;
            $value  = '';
            foreach ($fields as $field) {
                $value = $this->getField((string)$field);
                if ($value && $value != $id) {
                    break;
                }
            }

            return $value ? : $id;
        }


        $revision = $entity->hasCurrentRevision() ? $entity->getCurrentRevision() : $entity->getHead();

        if (!$revision) {
            return $id;
        }

        $value = $revision->get($field);

        return $value ? : $id;
    }

    protected function getId()
    {
        return $this->getObject()->getId();
    }

    protected function getKeywords()
    {
        $entity   = $this->getObject();
        $keywords = [];
        foreach ($entity->getTaxonomyTerms() as $term) {
            while ($term->hasParent()) {
                $keywords[] = $term->getName();
                $term       = $term->getParent();
            }
        }
        return array_unique($keywords);
    }

    /**
     * @return string
     */
    protected function getDescription()
    {
        return $this->getField(['summary', 'description', 'content']);
    }

    protected function getPreview()
    {
        return $this->getField(['summary', 'description', 'content']);
    }

    protected function getRouteName()
    {
        return 'entity/page';
    }

    protected function getRouteParams()
    {
        return [
            'entity' => $this->getObject()->getId()
        ];
    }

    protected function getTitle()
    {
        return $this->getField(['title', 'id']);
    }

    protected function getType()
    {
        return $this->getObject()->getType()->getName();
    }
}
