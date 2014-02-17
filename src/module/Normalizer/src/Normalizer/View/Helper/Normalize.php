<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Normalizer\View\Helper;

use Normalizer\NormalizerAwareTrait;
use Uuid\Entity\UuidInterface;
use Zend\View\Helper\AbstractHelper;

class Normalize extends AbstractHelper
{
    use NormalizerAwareTrait;

    public function __invoke($object = null)
    {
        if ($object === null) {
            return $this;
        }

        return $this->normalize($object);
    }

    public function toTitle($object)
    {
        return $this->normalize($object)->getTitle();
    }

    public function toPreview($object)
    {
        return $this->normalize($object)->getPreview();
    }

    public function toType($object)
    {
        return $this->normalize($object)->getType();
    }

    public function toUrl($object)
    {
        $normalized = $this->normalize($object);

        return $this->getView()->url($normalized->getRouteName(), $normalized->getRouteParams());
    }

    public function toAnchor($object)
    {
        $normalized = $this->normalize($object);

        return '<a href="' . $this->toUrl($object) . '">' . $normalized->getTitle() . '</a>';
    }

    protected function normalize($object)
    {
        if ($object instanceof UuidInterface) {
            $object = $object;
        }

        return $this->getNormalizer()->normalize($object);
    }
}