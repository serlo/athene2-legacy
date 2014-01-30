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
namespace Subject\Hydrator;

use Instance\Manager\InstanceManagerAwareTrait;
use Instance\Manager\InstanceManagerInterface;
use Subject\Manager\SubjectManagerAwareTrait;
use Subject\Manager\SubjectManagerInterface;
use Ui\Navigation\HydratorInterface;
use Zend\Stdlib\ArrayUtils;

class Navigation implements HydratorInterface
{
    use SubjectManagerAwareTrait, InstanceManagerAwareTrait;

    protected $path;

    public function __construct(InstanceManagerInterface $instanceManager, SubjectManagerInterface $subjectManager)
    {
        $this->subjectManager  = $subjectManager;
        $this->instanceManager = $instanceManager;
    }

    public function hydrateConfig(array &$config)
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $subjects = $this->getSubjectManager()->findSubjectsByInstance($instance);
        foreach ($subjects as $subject) {
            $config = ArrayUtils::merge(
                $config,
                include $this->path . $instance->getName() . '/' . strtolower(
                        $subject->getName()
                    ) . '/navigation.config.php'
            );
        }

        return $config;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }
}
