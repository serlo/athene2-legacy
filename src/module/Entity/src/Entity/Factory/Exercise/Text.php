<?php
namespace Entity\Factory\Exercise;

use Entity\Factory\AbstractEntityFactory;
use Entity\Factory\EntityFactoryInterface;

class Text extends AbstractEntityFactory implements EntityFactoryInterface {
    protected function _loadComponents(){
        $this->addRepositoryComponent()
        ->addRenderComponent('some/file/torender');
    }
}