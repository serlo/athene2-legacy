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
namespace Migrator\Controller;

use Migrator\Migrator;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;

class Worker extends AbstractActionController
{

    /**
     * @var Migrator
     */
    protected $migrator;

    /**
     * @var AuthenticationService
     */
    protected $authenticationService;

    /**
     * @param Migrator $migrator
     */
    public function __construct(Migrator $migrator, AuthenticationService $authenticationService)
    {
        $this->migrator = $migrator;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return Migrator
     */
    public function getMigrator()
    {
        return $this->migrator;
    }

    public function indexAction()
    {
        $adapter = $this->authenticationService->getAdapter();
        $adapter->setIdentity('legacy@serlo.org');
        $adapter->setCredential('123456');

        $this->authenticationService->authenticate();

        $this->getMigrator()->migrate();
        return '';
    }
}
 