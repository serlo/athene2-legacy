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
namespace LearningResource\Plugin\Taxonomy;

use LearningResource\Plugin\Taxonomy\TaxonomyPlugin;

abstract class TopicPlugin extends TaxonomyPlugin
{
    
    function getTopicTree ()
    {
        $return = array();
        foreach ($this->getTermManager()->getTerms() as $term) {
            $return[] = $this->getTermManager()->createInstance($term);
        }
        return $return;
    }
    
    function getRoots(){
        return $this->getSharedTaxonomyManager()->getTaxonomy('topic')->getRootTerms();
    }

    function getTopic ()
    {
        return $this->getEntityService()->getTerms()->getFromManager(0);
    }

    function setTopic ($term)
    {
        $current = $this->getTopic();
        $this->getEntityService()->getTerms()->removeElement($current);
        $current->get('entities')->removeElement($this->entityService->getEntity());
        $current->persist();
        
        $term = $this->getTermManager()->getTerm($term);
        $this->getEntityService()->getTerms()->add($term);
        $this->getEntityService()->persistAndFlush();
        
        return $this;
    }
}