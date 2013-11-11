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
namespace LearningResource\Plugin\Aggregate\Aggregator;


class TopicAggregator extends AbstractAggregator implements AggregatorInterface
{
    protected $whitelist = array(
        'topic',
        'topic-folder'
    );
    
    public function getName ()
    {
        return 'topic';
    }

	/* (non-PHPdoc)
     * @see \LearningResource\Plugin\Aggregate\Aggregator\AggregatorInterface::aggregate()
     */
    public function aggregate ()
    {
        $return = array();
        
        /* @var $plugin \LearningResource\Plugin\Taxonomy\TaxonomyPlugin */
        $plugin = $this->getObject()->plugin('taxonomy');

        /* @var $term \Taxonomy\Service\TermServiceInterface */
        foreach($plugin->getTerms() as $term){
            if(in_array($term->getTaxonomy()->getName(), $this->whitelist)){
                $return[] = new TopicResult($term);
            }
        }
        
        return $return;
    }
}