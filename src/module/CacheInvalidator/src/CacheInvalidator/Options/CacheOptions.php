<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Jakob Pfab (jakob.pfab@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace CacheInvalidator\Options;

use Zend\Stdlib\AbstractOptions;

class CacheOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $listens = [];

    /**
     * @return array
     */
    public function getListens()
    {
        return $this->listens;
    }

    /**
     * @param array $listens
     */
    public function setListens(array $listens)
    {
        $this->listens = $listens;
    }
}
