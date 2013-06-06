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
namespace Application\LearningObject\Component;

use Core\Component\AbstractComponent;
use Core\Component\ComponentInterface;
use Entity\Service\EntityServiceInterface;
use Taxonomy\TermManagerInterface;

class TopicComponent  extends AbstractComponent implements ComponentInterface
{

   
    protected $publicMethods = array('getTopicTree', 'getTopic');
    
    /**
     * 
     * @var TermManagerInterface
     */
    protected $termManager;
    
    /**
     * 
     * @var EntityServiceInterface
     */
    protected $entityService;
    

	/**
     * @return \Taxonomy\TermManagerInterface $termManager
     */
    public function getTermManager ()
    {
        return $this->termManager;
    }

	/**
     * @param \Taxonomy\TermManagerInterface $termManager
     * @return $this
     */
    public function setTermManager (TermManagerInterface $termManager)
    {
        $this->termManager = $termManager;
        return $this;
    }

	public function __construct (EntityServiceInterface $entityService){
	    $this->entityService = $entityService;
        $repository = $entityService->getEntity();
        $this->setTermManager($entityService->getSharedTaxonomyManager()->get('topic'));
        return $this;
    }

    function getTopicTree() {
        $return = array();
        foreach($this->getTermManager()->getTerms() as $term){
            $return[] = $this->getTermManager()->createInstance($term);
        }
        return $return;
    }
    
    function getTopic() {
        return $this->getTermManager()->get($this->entityService->get('terms')->get(0));
    }
}