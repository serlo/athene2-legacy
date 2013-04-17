<?php
namespace Auth\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Permissions extends AbstractPlugin
{

    /**
     * Adds a resource, manages the permissions and checks if the user has the rights to access it.
     *
     * $this->resource('Example\Controller\IndexController\Action',array(
     *     'guest' => 'deny',
     *     'login' => 'allow',
     * ));
     *
     * @return void
     */
    public function __invoke ($resource, array $permissions)
    {
        $auth = $this->getController()->auth();
        
        $auth->addPermissions(array(
            $resource => $permissions
        ));
        
        $auth->hasAccess($resource);
    }
}
