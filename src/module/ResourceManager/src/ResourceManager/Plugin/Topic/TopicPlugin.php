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
namespace ResourceManager\Plugin\Topic;

use Subject\Plugin\AbstractPlugin;
use Subject\Exception\InvalidArgumentException;

class TopicPlugin extends AbstractPlugin
{
    use \Taxonomy\Manager\SharedTaxonomyManagerTrait;
    
    public function addEntity($entity, $to){
        $term = $this->getSharedTaxonomyManager()->getTerm($to);
        
        if($term->getTaxonomy()->getSubject() !== $this->getSubjectService()->getEntity())
            throw new InvalidArgumentException(sprintf('Subject %s does not know topic %s', $this->getSubjectService()->getName(), $to));
        
        $term->addLink('entities', $entity->getEntity());
        $term->persistAndFlush();
        
        return $this;
    }

    public function getEnabledEntityTypes ()
    {
        $types = $this->getOption('entity_types');
        $return = array();
        foreach ($types as $type => $options) {
            $return[] = $type;
        }
        return $return;
    }
    
    public function isTypeEnabled($type){
        return in_array($type, $this->getEnabledEntityTypes());
    }

    public function getEntityTypeLabel ($type, $label)
    {
        if(!array_key_exists($type, $this->getOption('entity_types')))
            throw new \Exception(sprintf('Type %s is not registered.', $type));
        
        return $this->getOption('entity_types')[$type]['labels'][$label];
    }

    public function getTemplateForEntityType ($type)
    {
        if(!array_key_exists($type, $this->getOption('entity_types')))
            throw new \Exception(sprintf('Type %s is not registered.', $type));
            
        return $this->getOption('entity_types')[$type]['template'];
    }

    public function get ($topic)
    {
        return $this->getSubjectService()
            ->getTaxonomy('topic')
            ->get($topic);
    }

    public function getAll ()
    {
        $terms = $this->getSubjectService()->getTaxonomy('topic');
        $terms = $terms->getTerms();
        return $terms;
    }
}