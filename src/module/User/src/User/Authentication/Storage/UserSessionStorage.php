<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace User\Authentication\Storage;

use Zend\Authentication\Storage\Session;
use User\Exception\UserNotFoundException;

class UserSessionStorage extends Session
{
    use\Common\Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait;

    public function write($contents)
    {
        parent::write($contents->getId());
    }

    public function read()
    {
        $className = $this->getClassResolver()->resolveClassName('User\Entity\UserInterface');
        $id = parent::read();
        $user = $this->getObjectManager()->find($className, $id);
        if (! $user)
            throw new UserNotFoundException(sprintf('User %s not found', $id));
        return $user;
    }
}