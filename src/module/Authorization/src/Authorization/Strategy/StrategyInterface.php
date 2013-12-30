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

interface StrategyInterface
{
    /**
     * Returns true, if the object matches the strategies requirements
     *
     * @param  object $object            
     * @return bool
     */
    public function isValid($object);

    /**
     * Creates a callback for a permission and an object
     *
     * @param string $permission            
     * @param object $object            
     * @return callable
     */
    public function createAssertion($permission, $object);
}
