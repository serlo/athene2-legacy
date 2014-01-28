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
namespace Uuid\View\Helper;

use Uuid\Entity\UuidHolder;
use Uuid\Entity\UuidInterface;
use Uuid\Exception\InvalidArgumentException;
use Uuid\Options\ModuleOptions;
use Zend\View\Helper\AbstractHelper;

class UuidHelper extends AbstractHelper
{
    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @param ModuleOptions $moduleOptions
     */
    public function __construct(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
    }

    public function getPermission($object, $action)
    {
        if ($object instanceof UuidHolder) {
            $object = $object->getUuidEntity();
        } elseif ($object instanceof UuidInterface) {
        } else {
            throw new InvalidArgumentException;
        }

        return $this->moduleOptions->getPermission($object->getHolderName(), $action);
    }
}
