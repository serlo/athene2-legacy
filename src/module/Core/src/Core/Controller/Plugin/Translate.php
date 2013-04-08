<?php
namespace Core\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Translate extends AbstractPlugin
{

    function __invoke ($translate)
    {
        return $this->getController()->getServiceLocator()->get('translator')->translate($translate);
    }
}

?>