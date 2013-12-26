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
namespace Blog\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Blog\Exception;
use Zend\Stdlib\ArrayUtils;
use Blog\Entity\PostInterface;

class PostHydrator implements HydratorInterface
{

    protected $keys = [
        'author',
        'title',
        'publish',
        'content'
    ];

    public function extract($object)
    {
        $data = [];
        foreach ($this->keys as $key) {
            $method = 'get' . ucfirst($key);
            $data['key'] = $object->$method();
        }
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        $data = ArrayUtils::merge($this->extract($object), $data);
        
        if (! $object instanceof PostInterface) {
            throw new Exception\InvalidArgumentException(sprintf('Expected object to be PostInterface but got "%s"', get_class($object)));
        }
        
        foreach ($this->keys as $key) {
            $method = 'set' . ucfirst($key);
            $value = $this->getKey($data, $key);
            if ($value !== NULL) {
                $object->$method($value);
            }
        }
    }

    protected function getKey(array $data, $key)
    {
        return array_key_exists($key, $data) ? $data[$key] : NULL;
    }
}