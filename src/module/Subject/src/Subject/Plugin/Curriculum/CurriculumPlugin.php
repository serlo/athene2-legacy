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
use Entity\Collection\EntityCollection;

class CurriculumPlugin extends AbstractPlugin
{

    /**
     *
     * @return \ResourceManager\Plugin\Topic\TopicPlugin $topicPlugin
     */
    public function getTopicPlugin()
    {
        return $this->getSubjectService()->plugin('topic');
    }

    protected function filterEntityCollection(EntityCollection $collection)
    {}

    protected function filterTopicTree($tree)
    {}
    
    /*
     * public function addEntity($entity, $to){ $term = $this->getSharedTaxonomyManager()->getTerm($to); if($term->getTaxonomy()->getSubject() !== $this->getSubjectService()->getEntity()) throw new InvalidArgumentException(sprintf('Subject %s does not know topic %s', $this->getSubjectService()->getName(), $to)); $term->addLink('entities', $entity->getEntity()); $term->persistAndFlush(); return $this; }
     */
    public function getEnabledEntityTypes()
    {
        return $this->getTopicPlugin()->getEnabledEntityTypes();
    }

    public function isTypeEnabled($type)
    {
        return $this->getTopicPlugin()->isTypeEnabled($type);
    }

    public function getEntityTypeLabel($type, $label)
    {
        return $this->getTopicPlugin()->getEntityTypeLabel($type, $label);
    }

    public function getTemplateForEntityType($type)
    {
        return $this->getTopicPlugin()->getTemplateForEntityType($type);
    }

    public function get($topic)
    {
        return $this->getTopicPlugin()->get($topic);
    }

    public function getCurriculumTaxonomy()
    {
        return $this->getSubjectService()->getTaxonomy('taxonomy');
    }
}