<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */

namespace Instance\Manager;


use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Instance\Entity\InstanceInterface;

class InstanceEntityManager extends EntityManager
{

    /**
     * @var    InstanceInterface
     */
    protected $tenant;

    /**
     * @var    string        Multi tenant repository class
     */
    protected $multiTenantRepositoryClass;

    /**
     * @var    string        Multi tenant filtering field
     */
    protected $tenantField;

    /**
     * Return self instead of hardcoded EntityManager
     * {@inheritDoc}
     */
    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        if (!$config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        if (is_array($conn)) {
            $conn = DriverManager::getConnection($conn, $config, ($eventManager ? : new EventManager()));
        } else {
            if ($conn instanceof Connection) {
                if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                    throw ORMException::mismatchedEventManager();
                }
            } else {
                throw new \InvalidArgumentException("Invalid argument: " . $conn);
            }
        }

        return new self($conn, $config, $conn->getEventManager());
    }

    /**
     * Sets the default  multi tenant repo class
     *
     * @param    string        Classname to use
     */
    public function setMultiTenantRepositoryClass($class)
    {
        $this->multiTenantRepositoryClass = $class;
    }

    public function setTenantField($field)
    {
        $this->tenantField = $field;
    }

    /**
     * Gets the active Tenant
     *
     * @return    TenantInterface
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * Brings the Tenant into scope
     *
     * @param    TenantInterface
     */
    public function setTenant(InstanceInterface $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Check if $entity implements MultiTenantInterface
     * If it does, return MultiTenantEntityRepository
     * {@inheritDoc}
     */
    public function getRepository($entityName)
    {
        $entityName = ltrim($entityName, '\\');
        if (isset($this->repositories[$entityName])) {
            return $this->repositories[$entityName];
        }

        $metadata                  = $this->getClassMetadata($entityName);
        $customRepositoryClassName = $metadata->customRepositoryClassName;

        if ($customRepositoryClassName !== null) {
            $repository = new $customRepositoryClassName($this, $metadata);
            if ($this->tenant and $metadata->reflClass->implementsInterface(
                    'Instance\\Entity\\InstanceProviderInterface'
                )
            ) {
                $repository->setTenantField($this->tenantField);
            }
        } elseif ($this->tenant and $metadata->reflClass->implementsInterface(
                'Instance\\Entity\\InstanceProviderInterface'
            )
        ) {
            $repository = new $this->multiTenantRepositoryClass($this, $metadata);
            $repository->setTenantField($this->tenantField);
        } else {
            $repository = new EntityRepository($this, $metadata);
        }

        $this->repositories[$entityName] = $repository;

        return $repository;
    }
}
 