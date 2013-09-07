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
namespace Subject\Plugin\Topic;

use Subject\Plugin\AbstractPlugin;
use Subject\Exception\InvalidArgumentException;
use Taxonomy\Service\TermServiceInterface;

class TopicPlugin extends AbstractPlugin
{
    use \Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;
    
    public function addEntity($entity, $to){
        $term = $this->getSharedTaxonomyManager()->getTerm($to);
        
        if(!$term->knowsAncestor($this->getSubjectService()->getTermService()))
            throw new InvalidArgumentException(sprintf('Subject %s does not know topic %s', $this->getSubjectService()->getName(), $to));
        
        $term->addLink('entities', $entity->getEntity());
        $term->persistAndFlush();
        
        return $this;
    }
    
    public function getTopicPath(TermServiceInterface $term){
        return ($term->getTaxonomy()->getName() != 'subject') ? $this->getTopicPath($term->getParent()) . $term->getSlug() . '/' : '';
    }
    
    public function getTermManager(){
        return $this->getSharedTaxonomyManager()
            ->get('topic');        
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
        return $this->getSharedTaxonomyManager()
            ->get('topic')
            ->get($topic);
    }

    public function getAll ()
    {
        $terms = $this->getSubjectService()->getTaxonomy('topic');
        $terms = $terms->getTerms();
        return $terms;
    }

    public function getRootFolders ($subject){
        return $this->getSharedTaxonomyManager()->get('subject')->get($subject)->getChildrenByTaxonomyName('topic');
    }
}