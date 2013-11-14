<?php
namespace Common\Firewall;

use ZfcRbac\Firewall\AbstractFirewall;

class HydratableController extends AbstractFirewall
{

	use \Zend\ServiceManager\ServiceLocatorAwareTrait;
	
    /**
     *
     * @var array
     */
    protected $rules = array();

    /**
     *
     * @param array $rules            
     */
    public function __construct(array $rules)
    {
        foreach ($rules as $rule) {
        
        $provider = new $rule['role_provider'];
        $roles = $provider->getRoles($rule['controller'], (array) $rule['actions']);

        
            if (! is_array($roles)) {
                $roles = array(
                    $roles
                );
            }
            
            if (isset($rule['actions'])) {
                $rule['actions'] = (array) $rule['actions'];
                
                foreach ($rule['actions'] as $action) {
                    $this->rules[$rule['controller']][$action] = $roles;
                }
            } else {
                $this->rules[$rule['controller']] = $roles;
            }
        }
        
    }
    

    /**
     * Checks if access is granted to resource for the role.
     *
     * @param string $resource            
     * @return bool
     */
    public function isGranted($resource)
    {
      
        $resource = explode(':', $resource);
        $controller = $resource[0];
        $action = isset($resource[1]) ? $resource[1] : null;
        
        
        // Check action first
        if (isset($this->rules[$controller][$action])) {
            $roles = $this->rules[$controller][$action];
        } else return true;
        
        /*if (isset($this->rules[$controller])) {
            $roles = $this->rules[$controller];
        } else {
           $roles='guest';// return true;
        }*/ 
        
        return $this->rbac->hasRole($roles);
    }

    /**
     * Get the firewall name.
     *
     * @return string
     */
    public function getName()
    {
        return 'HydratableController';
    }
}
