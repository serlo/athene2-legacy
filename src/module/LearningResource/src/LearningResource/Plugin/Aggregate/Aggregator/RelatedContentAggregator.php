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

class RelatedContentAggregator extends AbstractAggregator implements AggregatorInterface
{
    use \RelatedContent\Manager\RelatedContentManagerAwareTrait;
    
    public function getName ()
    {
        return 'related-content';
    }

	/* (non-PHPdoc)
     * @see \LearningResource\Plugin\Aggregate\Aggregator\AggregatorInterface::aggregate()
     */
    public function aggregate ()
    {
        $return = array();
        
        $aggregated = $this->getRelatedContentManager()->aggregateRelatedContent($this->getObject()->getId());

        /* @var $related \RelatedContent\Result\ResultInterface */
        foreach($aggregated as $related){
            $return[] = new RelatedContentResult($related);
        }
        return $return;
    }

}