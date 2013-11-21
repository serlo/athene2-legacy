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
namespace LearningResource\Plugin\Provider;

use Entity\Plugin\AbstractPlugin;
use Entity\Exception;
use Taxonomy\Collection\TermCollection;
use Taxonomy\Service\TermServiceInterface;

class ProviderPlugin extends AbstractPlugin
{

    protected function getDefaultConfig()
    {
        return array(
            'fields' => array()
        );
    }

    public function get($field)
    {
        if (! is_string($field))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($field)));
        
        if (! array_key_exists($field, $this->getOption('fields')))
            throw new Exception\RuntimeException(sprintf('No configuration found for field %s', $field));
        
        $callback = $this->getOption('fields')[$field];
        return $callback($this->getEntityService());
    }

    public function getSubjectName()
    {
        $terms = $this->getEntityService()->plugin('taxonomy')->getTerms();
        return $this->iterTerms($terms)->getName();
    }

    public function getSubjectSlug()
    {
        $terms = $this->getEntityService()->plugin('taxonomy')->getTerms();
        return $this->iterTerms($terms)->getSlug();
    }
    
    protected function iterTerms(TermCollection $terms){
        foreach($terms as $term){
            $result = $this->iterTerm($term);
            if($result !== NULL)
                break;
        }
        
        if($result === NULL)
            throw new Exception\RuntimeException(sprintf('Could not find the subject'));
        
        return $result;
    }
    
    protected function iterTerm(TermServiceInterface $term){
        if($term->getTypeName() == 'subject'){
            return $term;
        } else {
            if($term->hasParent()){
                return $this->iterTerm($term->getParent());
            } else {
                return null;
            }
        }
    }
}