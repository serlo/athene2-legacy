<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author  Jakob Pfab (jakob.pfab@serlo.org)
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */

namespace Common\Guard;

use Zend\Mvc\MvcEvent;
use ZfcRbac\Service\AuthorizationService;
use ZfcRbac\Guard\AbstractGuard;

/**
 * A hydratable controller guard can protect a controller and a set of actions 
 * and hydrate the roles with a 
 */
class HydratableControllerGuard extends AbstractGuard
{
    use\Zend\ServiceManager\ServiceLocatorAwareTrait;
    use\Page\Manager\PageManagerAwareTrait;

    /**
     * Set a lower priority for controller guards than for route guards, so that they are
     * always executed after them
     */
    const EVENT_PRIORITY = - 20;

    /**
     * Rule prefix that is used to avoid conflicts in the Rbac container
     *
     * Rules will be added to the Rbac container using the following syntax:
     * __controller__.$controller.$action
     */
    const RULE_PREFIX = '__Hydcontroller__';

    /**
     * Controller guard rules
     *
     * @var array
     */
    protected $rules = [];

    protected $provider;

    /**
     * Constructor
     *
     * @param AuthorizationService $authorizationService            
     * @param array $rules            
     */
    public function __construct(AuthorizationService $authorizationService, array $rules = [])
    {
        parent::__construct($authorizationService);
        $this->setRules($rules);
    }

    /**
     * Set the rules (it overrides any existing rules)
     *
     * @param array $rules            
     * @return void
     */
    public function setRules(array $rules)
    {
        $this->rules = [];
        
        $this->addRules($rules);
    }

    /**
     * Add controller rules
     *
     * A controller rule is made the following way:
     *
     * array(
     * 'controller' => 'ControllerName',
     * 'actions' => []/string
     * 'roles' => []/string
     * )
     *
     * @param array $rules            
     * @return void
     */
    public function addRules(array $rules)
    {
        foreach ($rules as $rule) { 
            
            $controller = strtolower($rule['controller']);
            $actions = isset($rule['actions']) ? (array) $rule['actions'] : [];
            
            if (empty($actions)) {
                $this->rules[$controller][0] = $rule['role_provider'];
                continue;
            }
            
            foreach ($actions as $action) {
                $this->rules[$controller][strtolower($action)] = $rule['role_provider'];
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isGranted(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();
        $controller = strtolower($routeMatch->getParam('controller'));
        $action = strtolower($routeMatch->getParam('action'));
        
        // If no rules apply, it is considered as granted or not based on the protection policy
        if (! isset($this->rules[$controller])) {
            return true;
            //return $this->protectionPolicy === self::POLICY_ALLOW;
        }
        
        // Algorithm is as follow: we first check if there is an exact match (controller + action), if not
        // we check if there are rules set globally for the whole controllers (see the index "0"), and finally
        // if nothing is matched, we fallback to the protection policy logic
        

        if (isset($this->rules[$controller][$action])) {
            $providerName = $this->rules[$controller][$action];
        } elseif (isset($this->rules[$controller][0])) {
            $providerName = $this->rules[$controller][0];
        } else {
            return true;
            //return $this->protectionPolicy === self::POLICY_ALLOW;
        }
        
        $provider = new $providerName($event);
        $provider->setServiceLocator($this->getServiceLocator());
        $allowedRoles = $provider->getRoles();
        
        if (in_array('*', $allowedRoles)) {
            return true;
        }
        
        return $this->isAllowed($allowedRoles);
    }
}
    