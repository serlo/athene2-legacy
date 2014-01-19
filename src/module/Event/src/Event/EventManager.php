<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Event;

use ClassResolver\ClassResolverAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Event\Collection\EventCollection;
use Event\Exception;
use Language\Entity\LanguageInterface;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

class EventManager implements EventManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait, ClassResolverAwareTrait;

    protected $inMemoryEvents = array();

    protected $inMemoryParameterNames = array();

    public function findEventsByActor($userId)
    {
        if (!is_numeric($userId)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected numeric but got "%s"',
                gettype($userId)
            ));
        }

        $className  = $this->getClassResolver()->resolveClassName('Event\Entity\EventLogInterface');
        $repository = $this->getObjectManager()->getRepository($className);

        $results = $repository->findBy(
            [
                'actor' => $userId
            ],
            [
                'id' => 'desc'
            ]
        );

        $collection = new ArrayCollection($results);

        return $collection;
    }

    public function findEventsByObject($objectId, $recursive = true, array $filters = array())
    {
        if (!is_numeric($objectId)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected numeric but got `%s`',
                gettype($objectId)
            ));
        }

        $className  = $this->getClassResolver()->resolveClassName('Event\Entity\EventLogInterface');
        $repository = $this->getObjectManager()->getRepository($className);

        $results = $repository->findBy(
            [
                'uuid' => $objectId
            ]
        );

        if ($recursive) {
            $className = $this->getClassResolver()->resolveClassName('Event\Entity\EventParameterInterface');

            $parameters = $this->getObjectManager()->getRepository($className)->findBy(
                [
                    'uuid' => $objectId
                ]
            );

            /* @var $parameter \Event\Entity\EventParameterInterface */
            foreach ($parameters as $parameter) {
                if (!empty($filters)) {
                    if (in_array($parameter->getName(), $filters)) {
                        $results[] = $parameter->getLog();
                    }
                } else {
                    $results[] = $parameter->getLog();
                }
            }
        }

        $collection = [];
        foreach ($results as $result) {
            $collection[$result->getId()] = $result;
        }
        ksort($collection);
        rsort($collection);
        $collection = new ArrayCollection($collection);

        return $collection;
    }

    public function getEvent($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Event\Entity\EventLogInterface');
        $event     = $this->getObjectManager()->find($className, $id);
        if (!is_object($event)) {
            throw new Exception\EntityNotFoundException(sprintf('Could not find an Entity by the ID of `%d`', $id));
        }

        return $event;
    }

    public function logEvent(
        $uri,
        LanguageInterface $language,
        UserInterface $actor,
        UuidInterface $uuid,
        array $parameters = array()
    ) {
        $className = $this->getClassResolver()->resolveClassName('Event\Entity\EventLogInterface');

        /* @var $log Entity\EventLogInterface */
        $log = new $className();

        $log->setEvent($this->findTypeByName($uri));

        $log->setObject($uuid);
        $log->setActor($actor);
        $log->setLanguage($language);

        foreach ($parameters as $parameter) {
            $this->addParameter($log, $parameter);
        }

        $this->getObjectManager()->persist($log);

        return $log;
    }

    public function findTypeByName($name)
    {

        // Avoid MySQL duplicate entry on consecutive checks without flushing.
        if (array_key_exists($name, $this->inMemoryEvents)) {
            return $this->inMemoryEvents[$name];
        }

        $className = $this->getClassResolver()->resolveClassName('Event\Entity\EventInterface');
        $event     = $this->getObjectManager()->getRepository($className)->findOneBy(
            array(
                'name' => $name
            )
        );
        /* @var $event Entity\EventInterface */

        if (!is_object($event)) {
            $event = new $className();
            $event->setName($name);
            $this->getObjectManager()->persist($event);
            $this->inMemoryEvents[$name] = $event;
        }

        return $event;
    }

    /**
     * @param Entity\EventLogInterface $log
     * @param array                    $parameter
     * @throws Exception\RuntimeException
     * @return self
     */
    protected function addParameter(Entity\EventLogInterface $log, array $parameter)
    {
        if (!array_key_exists('object', $parameter)) {
            throw new Exception\RuntimeException(sprintf('No object given'));
        }
        if (!array_key_exists('name', $parameter)) {
            throw new Exception\RuntimeException(sprintf('No name given'));
        }
        if (!is_string($parameter['name'])) {
            throw new Exception\RuntimeException(sprintf(
                'Parameter name should be string, but got `%s`',
                gettype($parameter['name'])
            ));
        }
        if (!$parameter['object'] instanceof UuidInterface) {
            throw new Exception\RuntimeException(sprintf(
                'Parameter name should be UuidInterface, but got `%s`',
                get_class($parameter['object'])
            ));
        }

        $name = $this->findParameterNameByName($parameter['name']);

        /* @var $entity \Event\Entity\EventParameterInterface */
        $entity = $this->getClassResolver()->resolve('Event\Entity\EventParameterInterface');
        $entity->setLog($log);
        $entity->setName($name);
        $entity->setObject($parameter['object']);
        $log->addParameter($entity);

        $this->getObjectManager()->persist($entity);

        return $this;
    }

    /**
     * @param string $name
     * @return \Event\Entity\EventParameterNameInterface
     */
    protected function findParameterNameByName($name)
    {

        // Avoid MySQL duplicate entry on consecutive checks without flushing.
        if (array_key_exists($name, $this->inMemoryParameterNames)) {
            return $this->inMemoryParameterNames[$name];
        }

        $className = $this->getClassResolver()->resolveClassName('Event\Entity\EventParameterNameInterface');
        /* @var $parameterName Entity\EventParameterNameInterface */
        $parameterName = $this->getObjectManager()->getRepository($className)->findOneBy(
            array(
                'name' => $name
            )
        );

        if (!is_object($parameterName)) {
            $parameterName = new $className();
            $parameterName->setName($name);
            $this->getObjectManager()->persist($parameterName);
            $this->inMemoryParameterNames[$name] = $parameterName;
        }

        return $parameterName;
    }
}