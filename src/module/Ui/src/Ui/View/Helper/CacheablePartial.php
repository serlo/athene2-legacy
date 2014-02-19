<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Ui\View\Helper;

use Exception;
use Zend\Cache\Storage\StorageInterface;
use Zend\View\Helper\Partial;
use Zend\View\Model\ModelInterface;

class CacheablePartial extends Partial {


    /**
     * @var \Zend\Cache\Storage\StorageInterface
     */
    protected $storage;

    public function __construct(StorageInterface $storage){
        $this->storage = $storage;
    }

    /**
     * Renders a template fragment within a variable scope distinct from the
     * calling View object. It proxies to view's render function
     *
     * @param  string|ModelInterface $name   Name of view script, or a view model
     * @param  array|object          $values Variables to populate in the view
     * @throws Exception
     * @return string|Partial
     */
    public function __invoke($name = null, $values = null)
    {
        if (0 == func_num_args()) {
            return $this;
        }

        $key = null;
        if($values !== null || $name instanceof ModelInterface){
            // We want value to be null because that could be template files which gather information otherwise

            try {
                $key = hash('sha512',  serialize($name) . ':' . serialize($values));
                if($this->storage->hasItem($key)){
                    $content = $this->storage->getItem($key);
                    $this->storage->touchItem($key);
                    //return $content;
                }
            } catch (Exception $e){
            }
        }

        // If we were passed only a view model, just render it.
        if ($name instanceof ModelInterface) {
            return $this->getView()->render($name);
        }

        if (is_scalar($values)) {
            $values = array();
        } elseif ($values instanceof ModelInterface) {
            $values = $values->getVariables();
        } elseif (is_object($values)) {
            if (null !== ($objectKey = $this->getObjectKey())) {
                $values = array($objectKey => $values);
            } elseif (method_exists($values, 'toArray')) {
                $values = $values->toArray();
            } else {
                $values = get_object_vars($values);
            }
        }

        $result = $this->getView()->render($name, $values);

        if($key) {
            $this->storage->setItem($key, $result);
        }

        return $result;
    }

}
