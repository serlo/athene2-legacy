<?php
namespace Entity\Factory\LearningObjects\Solution;

use Entity\Factory\AbstractEntityFactory;
use Entity\Factory\Components\RepositoryComponent;

abstract class AbstractSolution extends AbstractEntityFactory
{
	
	/**
	 * @var RepositoryComponent
	 */
	protected $_repository;
	
    protected function _loadComponents(){
                
        $repository = new RepositoryComponent($this->getSource());
        $this->_repository = $repository->build();
    }
}