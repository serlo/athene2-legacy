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
namespace Authorization\Strategy;

use ZfcRbac\Identity\IdentityInterface;
use Language\Entity\LanguageAwareInterface;

class LanguageAwareStrategy implements StrategyInterface
{
    /**
     * @see \Authorization\Strategy\StrategyInterface::isValid()
     */
    public function isValid($object)
    {
        return $object instanceof LanguageAwareInterface;
    }

    /**
     * @see \Authorization\Strategy\StrategyInterface::createAssertion()
     */
    public function createAssertion($permission, $object)
    {
        return function (IdentityInterface $identity = null) use($object)
        {
            if ($identity !== NULL) {
                foreach ($identity->getRoles() as $role) {
                    if ($role->hasPermission($object->getLanguage()->getPermission())) {
                        return true;
                    }
                }
            }
            return false;
        };
    }
}
