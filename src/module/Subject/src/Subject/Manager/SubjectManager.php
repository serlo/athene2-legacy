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
namespace Subject\Manager;

use Subject\Service\SubjectServiceInterface;

class SubjectManager extends AbstractManager implements SubjectManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Subject\Plugin\PluginManagerAwareTrait;

    protected $names = array();

    public function add (SubjectServiceInterface $service)
    {
        $this->names[$service->getName()] = $service->getId();
        $this->addInstance($service->getId(), $service);
        return $this;
    }

    public function get ($subject)
    {
        $this->injectInstances();
        if (is_numeric($subject)) {
            return $this->getInstance($subject);
        } else {
            return $this->getInstance($this->names[$subject]);
        }
    }

    public function getAllSubjects ()
    {
        $this->injectInstances();
        return $this->getInstances();
    }

    public function has ($subject)
    {
        return $this->hasInstance($subject);
    }

    private function injectInstances ()
    {
        if (count($this->getInstances())) {
            return $this;
        }
        
        $em = $this->getObjectManager();
        $entities = $em->getRepository($this->resolveClassName('Subject\Entity\SubjectEntityInterface'))
            ->findAll();
        foreach ($entities as $entity) {
            $this->add($this->createInstanceFromEntity($entity));
        }
        return $this;
    }

    public function getSubjectFromRequest ()
    {
        return $this->get(1);
    }

    protected function createInstanceFromEntity ($entity)
    {
        if (! isset($this->config[$entity->getName()]))
            throw new \Exception(sprintf('Could not find a configuration for `%s`', $entity->getType()->getName()));
        $options = $this->config[$entity->getName()];
        
        $instance = $this->createInstance('Subject\Service\SubjectServiceInterface');
        $instance->setEntity($entity);
        $instance->setOptions($options);
        return $instance;
    }
}