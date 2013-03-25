<?php
namespace Auth\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Permissions\Acl\Resource\GenericResource as AclResource;

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
        $resource = strtolower($resource);
        
        $allowedRules = array(
            'allow',
            'deny'
        );

        $auth = $this->getController()->auth();
        $acl = $this->getController()->acl();
        $acl->addResource(new AclResource($resource));
        
        foreach ($permissions as $role => $value) {
                if (! in_array($value, $allowedRules)) {
                    throw new \Exception('Unallowed method `' . $value . '`. Use `allow` or `deny` only.');
                } else {
                    $acl->$value($role, $resource);
                }
        }
        
        if(! $auth->isAllowed($resource) ) {
            if($auth->loggedIn()){
                $this->getController()->getResponse()->setStatusCode(403);
                throw new \Exception('Du hast nicht die erforderlichen Rechte, um diese Seite zu sehen.');
            } else {
                $this->getController()->flashMessenger()->addSuccessMessage("Um diese Aktion auszuführen, musst du eingeloggt sein!");
                $this->getController()->redirect()->toRoute('login');
            }
        }
    }
}
