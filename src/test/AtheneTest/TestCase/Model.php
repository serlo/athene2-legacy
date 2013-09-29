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
namespace AtheneTest\TestCase;

abstract class Model extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @return array
     */
    abstract protected function getData();

    private $object;

    /**
     *
     * @return field_type $object
     */
    protected function getObject()
    {
        return $this->object;
    }

    /**
     *
     * @param field_type $object            
     * @return $this
     */
    protected function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    protected function inject($object)
    {
        foreach ($this->getData() as $key => $value) {
            $method = 'set' . ucfirst($key);
            $object->$method($value);
        }
        return $this;
    }

    public function testSetter()
    {
        $object = $this->getObject();
        $this->inject($object);
    }

    public function testGetter()
    {
        $object = $this->getObject();
        $this->inject($object);
        foreach ($this->getData() as $key => $value) {
            $method = 'get' . ucfirst($key);
            $this->assertEquals($value, $object->$method());
        }
    }
}