<?php
namespace Entity\LearningObjects\Solution;

class TextSolution extends AbstractSolution
{    
    protected function _loadComponents(){
        $this->setTemplate('display','entity/learning-objects/solution/text/display');
        parent::_loadComponents();
    }
    
    public function getContent ()
    {
        return $this->_repository->getCurrentRevision()->get('content');
    }
    
    public function _getViewModelData(){
        return array(
            'content' => $this->getContent()
        );
    }
    
    protected function _getJsonModelData(){
        return array(
            'id' => $this->getSource()->getId(),
            'content' => $this->getContent(),
        );
    }
    
    protected function _getFormData(){
        return array(
            'id' => $this->getSource()->get('id'),
            'content' => $this->getContent(),
        );
    }
}