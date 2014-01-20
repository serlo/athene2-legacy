<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Migrator\Worker;

use Doctrine\ORM\EntityManager;
use Migrator\Converter\ConverterChain;
use User\Manager\UserManagerInterface;
use Zend\Validator\EmailAddress;

class UserWorker implements Worker
{
    /**
     * @var EntityManager
     */
    protected $objectManager;

    /**
     * @var ConverterChain
     */
    protected $converterChain;

    /**
     * @var \User\Manager\UserManagerInterface
     */
    protected $userManager;

    public function __construct(
        EntityManager $objectManager,
        UserManagerInterface $userManagerInterface,
        ConverterChain $converterChain
    ) {
        $this->objectManager  = $objectManager;
        $this->userManager    = $userManagerInterface;
        $this->converterChain = $converterChain;
    }

    public function migrate()
    {
        $users     = $this->objectManager->getRepository('Migrator\Entity\User');
        $validator = new EmailAddress();

        foreach ($users as $user) {
            if ($user->getEmail() == 'aeneas@q-mail.me' || !$validator->isValid($user->getEmail())) {
                continue;
            }

            $this->userManager->createUser(
                [
                    'email'    => $user->getEmail(),
                    'username' => $user->getUsername(),
                    'password' => $user->getPassword()
                ]
            );
        }

        $this->userManager->flush();
    }
}
