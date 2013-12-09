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
namespace Common\View\Helper;

use Common\Normalize\Normalizable;
use Zend\View\Helper\AbstractHelper;

class Normalize extends AbstractHelper
{

    public function __invoke(Normalizable $object = NULL)
    {
        if ($object === NULL)
            return $this;
        return $object->normalize();
    }

    public function toTitle(Normalizable $object)
    {
        return $object->normalize()->getTitle();
    }

    public function toPreview(Normalizable $object)
    {
        return $object->normalize()->getPreview();
    }

    public function toType(Normalizable $object)
    {
        return $object->normalize()->getType();
    }

    public function toUrl(Normalizable $object)
    {
        $normalized = $object->normalize();
        return $this->getView()->url($normalized->getRouteName(), $normalized->getRouteParams());
    }

    public function toAnchor(Normalizable $object)
    {
        $normalized = $object->normalize();
        return '<a href="' . $this->toUrl($object) . '">' . $normalized->getTitle() . '</a>';
    }
}