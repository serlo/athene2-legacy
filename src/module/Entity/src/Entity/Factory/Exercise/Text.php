<?php
namespace Entity\Factory\Exercise;

use Entity\Factory\AbstractEntityFactory;
use Entity\Factory\EntityFactoryInterface;

class Text extends AbstractExercise implements EntityFactoryInterface {
    protected function _loadComponents(){
        parent::_loadComponents();
    }

    public function getSolution(){
    	$this->getComponent('link')->find('Factory\Solution\Solution');
    }
}