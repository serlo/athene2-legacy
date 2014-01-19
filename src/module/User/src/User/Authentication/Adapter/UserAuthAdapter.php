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
namespace User\Authentication\Adapter;

use User\Exception\UserNotFoundException;
use Zend\Authentication\Result;

class UserAuthAdapter implements AdapterInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\User\Authentication\HashServiceAwareTrait;

    private $email, $password;

    /**
     *
     * @param string $email            
     * @return self
     */
    public function setIdentity($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     *
     * @param string $password            
     * @return self
     */
    public function setCredential($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        try {
            $user = $this->getObjectManager()
                ->getRepository('User\Entity\User')
                ->findOneBy(array(
                'email' => $this->email
            ));

            $role = $this->getObjectManager()
                ->getRepository('User\Entity\Role')
                ->findOneBy(array(
                'name' => 'login'
            ));
            
            $hashedPassword = $user->getPassword();
            $password = $this->getHashService()->hashPassword($this->password, $this->getHashService()
                ->findSalt($hashedPassword));
            if ($password === $hashedPassword) {
                if ($user->isTrashed()) {
                    return new Result(RESULT::FAILURE_IDENTITY_NOT_FOUND, $this->email, array(
                        'Ihr Benutzerkonto wurde gelöscht.'
                    ));
                } elseif (! $user->hasRole($role)) {
                    return new Result(RESULT::FAILURE_IDENTITY_NOT_FOUND, $this->email, array(
                        'Sie haben ihren Account noch nicht aktiviert.'
                    ));
                } else {
                    return new Result(RESULT::SUCCESS, $user);
                }
            } else {
                return new Result(RESULT::FAILURE_CREDENTIAL_INVALID, $this->email, array(
                    'Mit dieser Kombination ist bei uns kein Benutzer registriert.'
                ));
            }
        } catch (UserNotFoundException $e) {
            return new Result(RESULT::FAILURE_IDENTITY_NOT_FOUND, $this->email, array(
                'Mit dieser Kombination ist bei uns kein Benutzer registriert.'
            ));
        }
        
        return new Result(RESULT::FAILURE, $this->email, array(
            'Logischer Fehler beim Login.'
        ));
    }
}