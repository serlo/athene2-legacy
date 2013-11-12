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
namespace Related\Result;

abstract class AbstractResult implements ResultInterface
{

    protected $object;

    /**
     *
     * @return field_type $object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     *
     * @param field_type $object            
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }
}