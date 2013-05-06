<?php
namespace Entity\LearningObjects\Exercise;

use Entity\LearningObjects\Exercise\Form\TextExerciseForm;
use Zend\View\Model\ViewModel;

class TextExercise extends AbstractExercise implements TextExerciseInterface
{    
    public function toViewModel()
    {
        if(!$this->_viewModel){
            $this->_viewModel = new ViewModel(array('entity' => $this));
        }
        $this->_viewModel->setTemplate('entity/learning-objects/exercise/text/display');
        $this->_viewModel->addChild($this->getSolution()->toViewModel(), 'solution');
        return $this->_viewModel;
    }

    public function getContent ()
    {
        return $this->_repositoryComponent->getRepository()->getCurrentRevision()->get('content');
    }
    
    public function getSolution ()
    {
        return $this->_link->findChild('Solution\TextSolution');
    }
    
    public function getFormObject(){
        return new TextExerciseForm();
    }
    
    public function getData(){
        return array(
            'id' => $this->getId(),
            'subject' => $this->getSubject(),
            'topic' => $this->getTopic(),
            'content' => $this->getContent(),
        );
    }
    
    public function getFormData(){
        return array(
            'id' => $this->getId(),
            'revision' => array(
                'content' => $this->getContent(),
            )
        );
    }
}