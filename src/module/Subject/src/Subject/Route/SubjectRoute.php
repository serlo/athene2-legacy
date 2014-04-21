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

namespace Subject\Route;

use Instance\Manager\InstanceManagerAwareTrait;
use Subject\Exception;
use Subject\Manager\SubjectManagerAwareTrait;
use Subject\Manager\SubjectManagerInterface;
use Zend\Mvc\Router\Http\Segment;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface as Request;

class SubjectRoute extends Segment implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var string
     */
    protected $identifier;

    public function __construct($route, array $constraints = array(), array $defaults = array(), $identifier = '')
    {
        $this->defaults   = $defaults;
        $this->parts      = $this->parseRouteDefinition($route);
        $this->regex      = $this->buildRegex($this->parts, $constraints);
        $this->identifier = $identifier;
    }

    /**
     * factory(): defined by RouteInterface interface.
     *
     * @see    \Zend\Mvc\Router\RouteInterface::factory()
     * @param  array|Traversable $options
     * @return Segment
     * @throws Exception\InvalidArgumentException
     */
    public static function factory($options = array())
    {
        if ($options instanceof \Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        if (!isset($options['route'])) {
            throw new Exception\InvalidArgumentException('Missing "route" in options array');
        }

        if (!isset($options['identifier'])) {
            $options['identifier'] = 'subject';
        }

        if (!isset($options['constraints'])) {
            $options['constraints'] = array();
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        return new static($options['route'], $options['constraints'], $options['defaults'], $options['identifier']);
    }

    /**
     * @return SubjectManagerInterface
     */
    public function getInstanceManager()
    {
        // TODO: Wait for zf2 route refactor.
        return $this->getServiceLocator()->getServiceLocator()->get('Instance\Manager\InstanceManager');
    }

    /**
     * @return SubjectManagerInterface
     */
    public function getSubjectManager()
    {
        // TODO: Wait for zf2 route refactor.
        return $this->getServiceLocator()->getServiceLocator()->get('Subject\Manager\SubjectManager');
    }

    /**
     * Match a given request.
     *
     * @param  Request $request
     * @return RouteMatch|null
     */
    public function match(Request $request)
    {
        $routeMatch = parent::match($request);

        if (!$routeMatch) {
            return null;
        }

        $subject = $routeMatch->getParam($this->identifier);

        try {
            $this->getSubjectManager()->findSubjectByString($subject, $this->getInstanceManager()->getInstanceFromRequest());
            return $routeMatch;
        } catch (\Exception $e) {
            return null;
        }
    }
}
