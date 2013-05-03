<?php
namespace Entity\LearningObjects\Solution;

use Entity\Factory\AbstractEntityBuilder;
use Entity\Factory\Components\RepositoryComponent;

abstract class AbstractSolution extends AbstractEntityBuilder
{
	
	/**
	 * @var RepositoryComponent
	 */
	protected $_repository;
	
    protected function _loadComponents(){
        $repository = new RepositoryComponent($this->getSource());
        $this->_repository = $repository->build()->getRepository();
    }
}