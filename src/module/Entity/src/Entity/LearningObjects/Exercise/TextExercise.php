<?php
namespace Entity\LearningObjects\Exercise;

use Entity\Factory\Components\RepositoryComponent;

class TextExercise extends AbstractExercise implements TextExerciseInterface
{

    protected $_repository;

    protected function _loadComponents ()
    {
        parent::_loadComponents();
        
        $repository = new RepositoryComponent($this->getSource());
        $this->_repository = $repository->build();
    }

    public function getContent ()
    {
        return $this->_repository->getCurrentRevision()->get('content');
    }

    public function getSummary ()
    {
        return $this->_repository->getCurrentRevision()->get('summary');
    }

    public function getSolution ()
    {
        return $this->_link->findChild('Solution\TextSolution');
    }
}