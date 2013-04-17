<?php
namespace Entity\Factory;

use Entity\Factory\AbstractEntityFactory;
use Entity\Factory\EntityFactoryInterface;

class Folder extends AbstractEntityFactory implements EntityFactoryInterface {
    protected function _loadComponents(){
        $this->addRepositoryComponent()
        ->addRenderComponent('some/file/torender');
    }
}