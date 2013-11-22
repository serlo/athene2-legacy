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

use Entity\Plugin\AbstractPlugin;
use Taxonomy\Collection\TermCollection;

class TaxonomyPlugin extends AbstractPlugin
{
    use \Taxonomy\Manager\SharedTaxonomyManagerAwareTrait, \Common\Traits\ObjectManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array();
    }
    
    public function hasTerm($id){
        $term = $this->getSharedTaxonomyManager()->getTerm($id);
        return $term->isAssociated('entities', $this->getEntityService()
            ->getEntity());        
    }

    public function addToTerm($id)
    {
        $term = $this->getSharedTaxonomyManager()->getTerm($id);
        $term->associate('entities', $this->getEntityService()
            ->getEntity());
        return $this;
    }

    public function removeFromTerm($id)
    {
        $term = $this->getSharedTaxonomyManager()->getTerm($id);
        $term->removeAssociation('entities', $this->getEntityService()
            ->getEntity());
        return $this;
    }
    
    /**
     * 
     * @return \Taxonomy\Collection\TermCollection
     */
    public function getTerms(){
        return new TermCollection($this->getEntityService()
            ->getEntity()->getTerms(), $this->getSharedTaxonomyManager());
    }
}