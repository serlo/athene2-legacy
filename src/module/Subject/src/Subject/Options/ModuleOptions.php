<?php
/**
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author        Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license        LGPL-3.0
 * @license        http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Subject\Options;

use Subject\Exception;
use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{

    /**
     *
     * @var array
     */
    protected $instances = [];

    /**

     * @param string $name
     * @param string $instance
     * @throws Exception\RuntimeException
     * @return SubjectOptions
     */
    public function getInstance($name, $instance)
    {
        $name = strtolower($name);
        $instance = strtolower($instance);

        if (!array_key_exists($instance, $this->instances)) {
            throw new Exception\RuntimeException(sprintf('Language "%s" unkown.', $instance));
        }

        if (!array_key_exists($name, $this->instances[$instance])) {
            throw new Exception\RuntimeException(sprintf('Subject "%s" unkown.', $name));
        }

        $options = $this->instances[$instance][$name];
        return new SubjectOptions($options);
    }

    /**
     *
     * @param array $instances
     * @return self
     */
    public function setInstances(array $instances)
    {
        $this->instances = $instances;
        return $this;
    }
}