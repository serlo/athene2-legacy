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
namespace Entity\Plugin\Aggregate;


use Entity\Plugin\AbstractPlugin;
use Doctrine\Common\Collections\ArrayCollection;
use Entity\Plugin\Aggregate\Exception;

class AggregatePlugin extends AbstractPlugin
{
    
    /**
     * 
     * @var Aggregator\AggregatorInterface[]
     */
    protected $aggregators = array();
    
    protected function getDefaultConfig(){
        return array(
            'aggregators' => array()
        );
    }
    
    /**
     * 
     * @param Aggregator\AggregatorInterface $aggregator
     * @return $this
     */
    public function addAggregator(Aggregator\AggregatorInterface $aggregator){
        $this->aggregators[$aggregator->getName()] = $aggregator;
        return $this;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function aggregate(){
        $return = new ArrayCollection();
        foreach($this->getOption('aggregators') as $aggregator){
            
            if(array_key_exists($aggregator, $this->aggregators)){
                $aggregator = $this->aggregators[$aggregator];
            } else {
                throw new Exception\RuntimeException(sprintf('Aggregator `%s` not known.', $aggregator));
            }
            
            $aggregator->setObject($this->getEntityService());
            foreach($aggregator->aggregate() as $result){
                $return->add($result);
            }
        }
        return $return;
    }
}