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
namespace CommonTest\Fake;

class InstanceFake
{

    protected $id;

    /**
     *
     * @return field_type
     *         $id
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     *
     * @param field_type $id            
     * @return self
     */
    public function setId ($id)
    {
        $this->id = $id;
        return $this;
    }
}