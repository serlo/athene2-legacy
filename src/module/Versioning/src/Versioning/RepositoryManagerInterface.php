<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Versioning;

use Versioning\Entity\RepositoryInterface;
use Versioning\Service\RepositoryServiceInterface;
use Zend\EventManager\EventManagerAwareInterface;

interface RepositoryManagerInterface extends EventManagerAwareInterface
{

    /**
     * Returns a RepositoryService
     *
     * @param RepositoryInterface $repository
     * @return RepositoryServiceInterface
     */
    public function getRepository(RepositoryInterface $repository);
}
