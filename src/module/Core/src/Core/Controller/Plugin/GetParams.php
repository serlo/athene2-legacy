<?php
namespace Core\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GetParams extends AbstractPlugin
{
    public function __invoke(){
        return $this->getController()->getEvent()->getRouteMatch()->getParams();
    }
}