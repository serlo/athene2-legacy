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
namespace Subject\Plugin\Curriculum;

use Subject\Plugin\AbstractPlugin;
use Subject\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Taxonomy\Service\TermServiceInterface;
use Entity\Service\EntityServiceInterface;
use Entity\Entity\EntityInterface;

class FilterPlugin extends AbstractPlugin
{
    use\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Entity\Manager\EntityManagerAwareTrait;

    protected $curriculum, $topic;

    protected function getDefaultConfig ()
    {
        return array();
    }

    public function getTopic ()
    {
        return $this->topic;
    }

    public function setTopic ($path)
    {
        $this->topic = $this->getSharedTaxonomyManager()
            ->get('topic')
            ->get($path);
        return $this;
    }

    public function setCurriculum ($curriculumId)
    {
        $this->curriculum = $this->getSharedTaxonomyManager()->getTerm($curriculumId);
        return $this;
    }

    public function getCurriculum ()
    {
        return $this->curriculum;
    }

    public function removeEntity ($entity)
    {
        if ($entity instanceof EntityServiceInterface) {
            $entity = $entity->getEntity();
        } elseif ($entity instanceof EntityInterface) {} elseif (is_numeric($entity)) {
            $entity = $this->getEntityManager()
                ->get($entity)
                ->getEntity();
        } else {
            throw new InvalidArgumentException('Parameter 1 expects to be numeric or implementing Entity\Service\EntityServiceInterface');
        }
        
        $this->getCurriculum()->removeLink('entities', $entity);
        return $this;
    }

    public function getTermManager ()
    {
        return $this->getSharedTaxonomyManager()->get('curriculum');
    }

    public function filterEntities (Collection $entities, TermServiceInterface $topic)
    {
        $termManager = $this->getTermManager();
        $curriculum = $this->getCurriculum();
        return $entities->filter(function  ($entity) use( $curriculum)
        {
            return $curriculum->hasLink('entities', $entity);
        });
    }

    public function filterTopicChildren (Collection $children)
    {
        $collection = new ArrayCollection();
        foreach ($children as $child) {
            if ($this->filterTopic($child))
                $collection->add($child);
        }
        return $collection;
    }

    public function getTopicPath (TermServiceInterface $term)
    {
        return ($term->getTaxonomy()->getName() != 'subject') ? $this->getTopicPath($term->getParent()) . $term->getSlug() . '/' : '';
    }

    public function get ($curriculum)
    {
        return $this->getTermManager()->get($curriculum);
    }

    public function getRootTopics ()
    {
        $return = $this->getSubjectService()
            ->getTermService()
            ->getChildrenByTaxonomyName('topic');
        return $return;
    }

    protected function filterTopic (TermServiceInterface $topic)
    {
        if ($topic->isLinkAllowed('entities') && $topic->hasLinks('entities')) {
            return true;
        }
        
        if ($topic->hasChildren()) {
            foreach ($topic->getChildren() as $child) {
                if ($this->filterTopic($child))
                    return true;
            }
        }
        return false;
    }

    public function addEntity ($entity, $to)
    {
        $term = $this->getSharedTaxonomyManager()->getTerm($to);
        
        if ($term->getTaxonomy()->getSubject() !== $this->getSubjectService()->getEntity())
            throw new InvalidArgumentException(sprintf('Subject %s does not know topic %s', $this->getSubjectService()->getName(), $to));
        
        $term->addLink('entities', $entity->getEntity());
        $term->persistAndFlush();
        
        return $this;
    }

    public function getEnabledEntityTypes ()
    {
        return $this->getSubjectService()
            ->topic()
            ->getEnabledEntityTypes();
    }

    public function isTypeEnabled ($type)
    {
        return $this->getSubjectService()
            ->topic()
            ->isTypeEnabled($type);
    }

    public function getEntityTypeLabel ($type, $label)
    {
        return $this->getSubjectService()
            ->topic()
            ->getEntityTypeLabel($type, $label);
    }

    public function getTemplateForEntityType ($type)
    {
        return $this->getSubjectService()
            ->topic()
            ->getTemplateForEntityType($type);
    }

    public function getRootFolders ()
    {
        return $this->getTermManager()->getRootTerms();
    }

    public function getSchoolTypeRootFolders ()
    {
        return $this->getSharedTaxonomyManager()
            ->get('school-type')
            ->getRootTerms();
    }
}