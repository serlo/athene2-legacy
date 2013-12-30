<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	    LGPL-3.0
 * @license	    http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright	Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Authorization\Guard;

use ZfcRbac\Guard\ControllerGuard;
use Zend\Mvc\MvcEvent;
use Authorization\Assertion\ControllerAssertionInterface;
use Authorization\Exception;

class AssertiveControllerGuard extends ControllerGuard
{
    use\Zend\ServiceManager\ServiceLocatorAwareTrait;

    /**
     * @see \ZfcRbac\Guard\ControllerGuard::setRules()
     */
    public function setRules(array $rules)
    {
        $this->rules = [];
        
        foreach ($rules as $rule) {
            $controller = strtolower($rule['controller']);
            $actions = isset($rule['actions']) ? (array) $rule['actions'] : [];
            $roles = (array) $rule['roles'];
            $assertion = isset($rule['assertion']) ? $rule['assertion'] : false;
            
            if (empty($actions)) {
                $this->rules[$controller][0]['roles'] = $roles;
                $this->rules[$controller][0]['assertion'] = $assertion;
                continue;
            }
            
            foreach ($actions as $action) {
                $this->rules[$controller][strtolower($action)]['roles'] = $roles;
                $this->rules[$controller][strtolower($action)]['assertion'] = $assertion;
            }
        }
    }

    public function isGranted(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();
        $controller = strtolower($routeMatch->getParam('controller'));
        $action = strtolower($routeMatch->getParam('action'));
        
        // If no rules apply, it is considered as granted or not based on the protection policy
        if (! isset($this->rules[$controller])) {
            return $this->protectionPolicy === self::POLICY_ALLOW;
        }
        
        // Algorithm is as follow: we first check if there is an exact match (controller + action), if not
        // we check if there are rules set globally for the whole controllers (see the index "0"), and finally
        // if nothing is matched, we fallback to the protection policy logic
        
        if (isset($this->rules[$controller][$action])) {
            $allowedRoles = $this->rules[$controller][$action]['roles'];
            $assertion = $this->rules[$controller][$action]['assertion'];
        } elseif (isset($this->rules[$controller][0])) {
            $allowedRoles = $this->rules[$controller][0]['roles'];
            $assertion = $this->rules[$controller][0]['assertion'];
        } else {
            return $this->protectionPolicy === self::POLICY_ALLOW;
        }
        
        if (in_array('*', $allowedRoles)) {
            return ($assertion) ? $this->assert($assertion) : true;
        }
        
        if ($this->roleService->matchIdentityRoles($allowedRoles)) {
            return ($assertion) ? $this->assert($assertion) : true;
        }
        
        return false;
    }

    /**
     *
     * @param callable|AssertionInterface|string $assertion            
     * @return bool
     * @throws Exception\InvalidArgumentException
     */
    protected function assert($assertion)
    {
        $identity = $this->roleService->getIdentity();
        
        if (is_callable($assertion)) {
            return $assertion($identity);
        } elseif ($assertion instanceof ControllerAssertionInterface) {
            return $assertion->assert($identity);
        } elseif (is_string($assertion)) {
            $assertion = $this->getServiceLocator()->get($assertion);
            return $assertion->assert($identity);
        }
        
        throw new Exception\InvalidArgumentException(sprintf('Assertions must be callable, string or implement ZfcRbac\Assertion\AssertionInterface, "%s" given', is_object($assertion) ? get_class($assertion) : gettype($assertion)));
    }
}