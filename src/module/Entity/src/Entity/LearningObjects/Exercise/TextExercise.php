<?php
namespace Entity\LearningObjects\Exercise;

use Entity\Factory\Components\RepositoryComponent;
use Entity\LearningObjects\Exercise\Form\TextExerciseForm;

class TextExercise extends AbstractExercise implements TextExerciseInterface
{

    protected $_repository;

    protected function _loadComponents ()
    {
        parent::_loadComponents();
        
        $repository = new RepositoryComponent($this->getSource());
        $this->_repository = $repository->build()->getRepository();
        
        $this->setTemplate('display', 'entity/learning-objects/exercise/text/display');
        $this->setTemplate('form', 'entity/learning-objects/exercise/text/form');
        $this->setForm(new TextExerciseForm());
    }

    public function getContent ()
    {
        return $this->_repository->getCurrentRevision()->get('content');
    }
    
    public function getSolution ()
    {
        return $this->_link->findChild('Solution\TextSolution');
    }
    
    protected function _getViewModelData(){
        return array(
            'id' => $this->getSource()->getId(),
            'subject' => $this->getSubject(),
            'topic' => $this->getTopic(),
            'content' => $this->getContent(),
            'solution' => $this->getSolution()->toViewModel('display'),
        );
    }
    
    protected function _getJsonModelData(){
        return array(
            'id' => $this->getSource()->getId(),
            'subject' => $this->getSubject(),
            'topic' => $this->getTopic(),
            'content' => $this->getContent(),
            'solution' => $this->getSolution()->toJsonModel('display'),
        );
    }
    
    protected function _getFormData(){
        return array(
            'content' => $this->getContent(),
        );
    }
}