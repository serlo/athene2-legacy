<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft f√ºr freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Subject\Manager;

use Language\Entity\LanguageInterface;
use Entity\Entity\EntityInterface;
use Doctrine\Common\Collections\Collection;
use Taxonomy\Entity\TaxonomyTermInterface;
use Doctrine\Common\Collections\ArrayCollection;

class SubjectManager implements SubjectManagerInterface
{
    use\Taxonomy\Manager\TaxonomyManagerAwareTrait;

    public function getSubject($id)
    {
        $term = $this->getTaxonomyManager()->getTerm($id);
        
        return $term;
    }

    public function findSubjectByString($name, LanguageInterface $language)
    {
        $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName('subject', $language);
        $term = $this->getTaxonomyManager()->findTerm($taxonomy, (array) $name);
        return $term;
    }

    public function findSubjectsByLanguage(LanguageInterface $language)
    {
        $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName('subject', $language);
        return $taxonomy->getChildren();
    }

    public function getTrashedEntities(TaxonomyTermInterface $term)
    {
        $entities = $this->getEntities($term);
        $collection = new ArrayCollection();
        $this->iterEntities($entities, $collection, 'isTrashed');
        return $collection;
    }

    public function getUnrevisedEntities(TaxonomyTermInterface $term)
    {
        $entities = $this->getEntities($term);
        $collection = new ArrayCollection();
        $this->iterEntities($entities, $collection, 'isRevised');
        return $collection;
    }

    protected function getEntities(TaxonomyTermInterface $term)
    {
        return $term->getAssociatedRecursive('entities', array(
            'abstract-topic',
            'topic',
            'topic-folder',
            'subject',
            'curriculum',
            'curriculum-folder',
            'school-type'
        ));
    }

    protected function iterEntities(Collection $entities, Collection $collection, $callback)
    {
        foreach ($entities as $entity) {
            $this->$callback($entity, $collection);
            $this->iterLinks($entity, $collection, $callback);
        }
    }

    protected function iterLinks(EntityInterface $entity, $collection, $callback)
    {
        $this->iterEntities($entity->getChildren('link'), $collection, $callback);
    }

    protected function isRevised(EntityInterface $entity, Collection $collection)
    {
        if ($entity->isUnrevised() && ! $collection->contains($entity)) {
            $collection->add($entity);
        }
    }

    protected function isTrashed(EntityInterface $entity, Collection $collection)
    {
        if ($entity->getTrashed() === TRUE) {
            $collection->add($entity);
        }
    }
}