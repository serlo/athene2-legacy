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
namespace Subject\Plugin\Taxonomy;

use Subject\Plugin\AbstractPlugin;
use Subject\Exception\InvalidArgumentException;
use Taxonomy\Service\TermServiceInterface;

class TaxonomyPlugin extends AbstractPlugin
{
    use \Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'entity_types' => array(),
            'taxonomy' => 'topic',
            'taxonomy_parent' => 'subject',
            'templates' => array(
                'index' => 'subject/plugin/taxonomy/index',
                'taxonomy' => 'subject/plugin/taxonomy/taxonomy',
                'links' => 'subject/plugin/taxonomy/links'
            )
        );
    }

    public function addEntity($entity, $to)
    {
        $term = $this->getSharedTaxonomyManager()->getTerm($to);
        
        if (! $term->knowsAncestor($this->getSubjectService()
            ->getTermService()))
            throw new InvalidArgumentException(sprintf('Subject %s does not know topic %s', $this->getSubjectService()->getName(), $to));
        
        $term->associate('entities', $entity->getEntity());
        $term->persistAndFlush();
        
        return $this;
    }

    public function getPathToTermAsUri(TermServiceInterface $term)
    {
        return substr($this->_getPathToTermAsUri($term), 0 , -1);
    }
    
    private function _getPathToTermAsUri(TermServiceInterface $term){
        return ($term->getTaxonomy()->getName() != $this->getOption('taxonomy_parent')) ? $this->_getPathToTermAsUri($term->getParent()) . $term->getSlug() . '/' : '';        
    }

    public function getTermManager()
    {
        return $this->getSharedTaxonomyManager()->findTaxonomyByName($this->getOption('taxonomy'), $this->getSubjectService()
            ->getLanguageService());
    }

    public function getEnabledEntityTypes()
    {
        $types = $this->getOption('entity_types');
        $return = array();
        foreach ($types as $type => $options) {
            $return[] = $type;
        }
        return $return;
    }

    public function isTypeEnabled($type)
    {
        return in_array($type, $this->getEnabledEntityTypes());
    }

    public function getEntityTypeLabel($type, $label)
    {
        if (! array_key_exists($type, $this->getOption('entity_types')))
            throw new \Exception(sprintf('Type %s is not registered.', $type));
        
        return $this->getOption('entity_types')[$type]['labels'][$label];
    }

    public function getTemplateForEntityType($type)
    {
        if (! array_key_exists($type, $this->getOption('entity_types')))
            throw new \Exception(sprintf('Type %s is not registered.', $type));
        
        return $this->getOption('entity_types')[$type]['template'];
    }

    public function get($term)
    {
        return $this->getTermManager()->getTerm($term);
    }

    public function findTermByAncestors($ancestors)
    {
        return $this->getTermManager()->findTermByAncestors($ancestors);
    }

    public function getAll()
    {
        return $this->getTermManager()->getTerms();
    }

    public function getRootFolders($taxonomyParentType)
    {
        $return = $this->getSharedTaxonomyManager()
            ->findTaxonomyByName($this->getOption('taxonomy_parent'), $this->getSubjectService()
            ->getLanguageService())
            ->findTermByAncestors((array) $taxonomyParentType)
            ->findChildrenByTaxonomyName($this->getOption('taxonomy'));
        return $return;
    }
}