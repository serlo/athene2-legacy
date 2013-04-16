<?php
namespace Core\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GetParam extends AbstractPlugin
{
    public function __invoke($param, $value = NULL){
        return $this->getController()->getEvent()->getRouteMatch()->getParam($param, $value);
    }
}