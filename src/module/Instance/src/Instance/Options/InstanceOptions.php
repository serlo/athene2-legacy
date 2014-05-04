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
namespace Instance\Options;

use Zend\Stdlib\AbstractOptions;

class InstanceOptions extends AbstractOptions
{
    /**
     * @var bool
     */
    protected $useCookie = true;

    /**
     * @var int
     */
    protected $default = 1;

    /**
     * @return int
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param int $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * @return boolean
     */
    public function getUseCookie()
    {
        return $this->useCookie;
    }

    /**
     * @param boolean $useCookie
     */
    public function setUseCookie($useCookie)
    {
        $this->useCookie = $useCookie;
    }
}
