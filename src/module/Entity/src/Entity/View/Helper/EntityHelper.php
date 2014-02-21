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

use Entity\Entity\EntityInterface;
use Entity\Exception;
use Entity\Options\EntityOptions;
use Zend\View\Helper\AbstractHelper;

class EntityHelper extends AbstractHelper
{
    use \Entity\Options\ModuleOptionsAwareTrait;

    /**
     * @param EntityInterface $entity
     * @return EntityOptions
     */
    public function getOptions(EntityInterface $entity)
    {
        return $this->getModuleOptions()->getType(
            $entity->getType()->getName()
        );
    }

    public function renderDiscussions(EntityInterface $entity)
    {
        $view  = $this->getView();
        $uuid  = $entity;
        $forum = [
            $this->findTaxonomyTerm($entity, 'subject')->getName(),
            $entity->getType()->getName()
        ];

        return $view->discussion($uuid)->findForum($forum)->render();
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
        throw new Exception\RuntimeException(sprintf('Entity does not have an taxonomy term ancestor "%s"', $type));
    }
}