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
use Instance\Entity\InstanceProviderInterface;

class InstanceAwareEntityManager extends EntityManager
{

    /**
     * @var InstanceInterface
     */
    protected $instance;

    /**
     * @var string
     */
    protected $instanceAwareRepositoryClassName = 'Instance\Repository\InstanceAwareRepository';

    /**
     * @var string
     */
    protected $instanceProviderRepositoryClassName = 'Instance\Repository\InstanceProviderRepository';

    /**
     * @var string
     */
    protected $instanceField = 'instance';

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
     * {@inheritDoc}
     */
    public function find($entityName, $id, $lockMode = null, $lockVersion = null)
    {
        $entity = parent::find($entityName, $id, $lockMode, $lockVersion);
        if ($entity instanceof InstanceProviderInterface) {
            if ($entity->getInstance() === $this->getInstance()) {
                return $entity;
            }
            return null;
        }
        return $entity;
    }

    /**
     * @return InstanceInterface
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param InstanceInterface $instance
     */
    public function setInstance(InstanceInterface $instance)
    {
        $this->instance = $instance;
    }

    /**
     * Check if $entity implements InstanceProviderInterface
     * If it does, return InstanceAwareEntityRepository
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
        $instanceProviderInterface = 'Instance\\Entity\\InstanceProviderInterface';
        $instanceAwareInterface    = 'Instance\\Entity\\InstanceAwareInterface';

        if ($customRepositoryClassName !== null) {
            $repository = new $customRepositoryClassName($this, $metadata);
            if ($this->instance && $metadata->reflClass->implementsInterface($instanceAwareInterface)) {
                $repository->setInstanceField($this->instanceField);
            }
        } elseif ($this->instance && $metadata->reflClass->implementsInterface($instanceAwareInterface)) {
            $repository = new $this->instanceAwareRepositoryClassName($this, $metadata);
            $repository->setInstanceField($this->instanceField);
        } elseif ($this->instance && $metadata->reflClass->implementsInterface($instanceProviderInterface)) {
            $repository = new $this->instanceProviderRepositoryClassName($this, $metadata);
        } else {
            $repository = new EntityRepository($this, $metadata);
        }

        $this->repositories[$entityName] = $repository;

        return $repository;
    }

    /**
     * Sets the default  multi tenant repo class
     *
     * @param    string        Classname to use
     */
    public function setInstanceAwareRepositoryClassName($class)
    {
        $this->instanceAwareRepositoryClassName = $class;
    }

    public function setInstanceField($field)
    {
        $this->instanceField = $field;
    }
}
 