<?php
namespace Entity\Factory\LearningObjects\Solution;

class TextSolution extends AbstractSolution
{    
    public function getContent ()
    {
        return $this->_repository->getCurrentRevision()->get('content');
    }
}