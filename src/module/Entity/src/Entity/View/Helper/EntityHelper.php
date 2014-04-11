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
namespace Entity\View\Helper;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity\Entity\EntityInterface;
use Entity\Exception;
use Entity\Options\ModuleOptionsAwareTrait;
use Zend\View\Helper\AbstractHelper;

class EntityHelper extends AbstractHelper
{
    use ModuleOptionsAwareTrait;

    public function __invoke()
    {
        return $this;
    }

    public function findTaxonomyTerm(EntityInterface $entity, $type)
    {
        /* @var $term \Taxonomy\Entity\TaxonomyTermInterface */
        foreach ($entity->getTaxonomyTerms() as $term) {
            $ancestor = $term->findAncestorByTypeName($type);
            if ($ancestor) {
                return $ancestor;
            }
        }

        return null;
    }

    public function getVisible(Collection $entities)
    {
        return $entities->filter(
            function (EntityInterface $e) {
                return !$e->isTrashed() && $e->hasCurrentRevision();
            }
        );
    }

    public function asTypeCollection(Collection $entities)
    {
        $types = [];
        foreach ($entities as $e) {
            $types[$e->getType()->getName()][] = $e;
        }

        return new ArrayCollection($types);
    }

    public function getOptions(EntityInterface $entity)
    {
        return $this->getModuleOptions()->getType(
            $entity->getType()->getName()
        );
    }

    public function renderDiscussions(EntityInterface $entity, $type = 'subject')
    {
        $view    = $this->getView();
        $uuid    = $entity;
        $subject = $this->findTaxonomyTermInAncestorOrSelf($entity, $type);
        $forum   = [$subject->getName(), $entity->getType()->getName()];

        return $view->discussion($uuid)->findForum($forum)->render();
    }

    protected function findTaxonomyTermInAncestorOrSelf(EntityInterface $entity, $type)
    {
        // Check self
        $subject = $this->findTaxonomyTerm($entity, $type);
        if ($subject) {
            return $subject;
        }

        // Check parents
        foreach ($entity->getParents('link') as $parent) {
            $subject = $this->findTaxonomyTerm($parent, $type);
            if ($subject) {
                return $subject;
            }
        }

        // Last resort: check ancestors
        foreach ($entity->getParents('link') as $parent) {
            $subject = $this->findTaxonomyTermInAncestorOrSelf($parent, $type);
            if ($subject) {
                return $subject;
            }
        }

        // Nothing found
        throw new Exception\RuntimeException(sprintf('Entity does not have an taxonomy term ancestor "%s"', $type));
    }
}