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

class TaxonomyPlugin extends AbstractPlugin
{
    use \Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    /* (non-PHPdoc)
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
    */
    public function attach (\Zend\EventManager\EventManagerInterface $events)
    {
        // TODO Auto-generated method stub
    
    }
    
    /* (non-PHPdoc)
     * @see \Zend\EventManager\ListenerAggregateInterface::detach()
    */
    public function detach (\Zend\EventManager\EventManagerInterface $events)
    {
        // TODO Auto-generated method stub
    
    }

}