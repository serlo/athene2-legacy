<?php
namespace Entity\Factory\Exercise;

use Entity\Factory\AbstractEntityFactory;

abstract class AbstractExercise extends AbstractEntityFactory
{
    protected function _loadComponents(){
        $this->addRepositoryComponent()
        //->addRenderComponent('some/file/torender')
        ->addSubjectComponent()
        //->addTopicComponent()
        ;
    }
        
    public function render(){
        //$this->getComponent('render')->render();
    }
    
    
}