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
namespace Subject\Router;

use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ArrayUtils;
use Zend\Mvc\Router\Exception\InvalidArgumentException;

class SubjectRouter implements RouteInterface
{

    protected $defaults = array();

    /**
     * Create a new page route.
    */
    public function __construct(array $defaults = array())
    {
        $this->defaults = $defaults;
    }

    /**
     * Create a new route with given options.
     */
    public static function factory($options = array())
    {
        if ($options instanceof \Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        return new static($options['defaults']);
    }


    /**
     * Match a given request.
     */
    public function match(Request $request, $pathOffset = null)
    {
        //@todo test the Request object and return a \Zend\Mvc\Router\RouteMatch instance
        return null;
    }

    /**
     * Assemble the route.
     */
    public function assemble(array $params = array(), array $options = array())
    {
        //@todo assemple the route and return the URL as string
        return '';
    }

    /**
     * Get a list of parameters used while assembling.
     */
    public function getAssembledParams()
    {
        return array();
    }

}