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
namespace Authorization\Service;

use Authorization\Strategy\StrategyInterface;

class AuthorizationService implements AuthorizationServiceInterface
{
    
    use \ZfcRbac\Service\AuthorizationServiceAwareTrait;

    /**
     * The strategies registered by default
     *
     * @var array
     */
    protected $strategies = [
        'Authorization\Strategy\LanguageAwareStrategy',
        'Authorization\Strategy\PermissionAwareStrategy'
    ];

    /**
     * @see \Authorization\Service\AuthorizationServiceInterface::isGranted()
     */
    public function isGranted($permission, $assertionOrObject = NULL)
    {
        $assertion = $assertionOrObject;
        foreach ($this->strategies as $strategy) {
            if (! $strategy instanceof StrategyInterface) {
                $this->strategies[$strategy] = new $strategy();
                $strategy = $this->strategies[$strategy];
            }
            
            if ($strategy->isValid($assertionOrObject)){
                $assertion = $strategy->createAssertion($permission, $assertionOrObject);
            }
        }
        
        return $this->getAuthorizationService()->isGranted($permission, $assertion);
    }
}
