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
namespace Authorization\Assertion;

use ZfcRbac\Identity\IdentityInterface;

class LanguageAssertion implements ControllerAssertionInterface
{
    use \Language\Manager\LanguageManagerAwareTrait;

    public function assert(IdentityInterface $identity = NULL)
    {
        if ($identity === null) {
            return false;
        }
        
        $language = $this->getLanguageManager()->getLanguageFromRequest();
        
        foreach ($identity->getRoles() as $role) {
            if ($role->hasPermission($language->getPermission())) {
                return true;
            }
        }
        
        return false;
    }
}