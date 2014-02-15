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
namespace Normalizer\Strategy;

use Common\Filter\PreviewFilter;
use Normalizer\Entity\Normalized;
use Normalizer\Exception\RuntimeException;

abstract class AbstractStrategy implements StrategyInterface
{

    protected $object;

    public function getObject()
    {
        return $this->object;
    }

    public function normalize($object)
    {
        if (!$this->isValid($object)) {
            throw new RuntimeException(sprintf(
                'I don\'t know how to normalize "%s", maybe you used the wrong strategy?',
                get_class($object)
            ));
        }

        $this->setObject($object);

        $preview = $this->getPreview();
        $filter  = new PreviewFilter();
        $preview = $filter->filter($preview);

        $title  = $this->getTitle();
        $filter = new PreviewFilter(200, '...');
        $title  = $filter->filter($title);
        $timestamp = $this->getTimestamp() ? $this->getTimestamp() : new \DateTime();

        $normalized = new Normalized();
        $normalized->setTitle($title);
        $normalized->setTimestamp($timestamp);
        $normalized->setContent($this->getContent());
        $normalized->setPreview($preview);
        $normalized->setType($this->getType());
        $normalized->setRouteName($this->getRouteName());
        $normalized->setRouteParams($this->getRouteParams());

        return $normalized;
    }

    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return string
     */
    abstract protected function getTitle();

    /**
     * @return string
     */
    abstract protected function getTimestamp();

    /**
     * @return string
     */
    abstract protected function getContent();

    /**
     * @return string
     */
    abstract protected function getPreview();

    /**
     * @return string
     */
    abstract protected function getType();

    /**
     * @return string
     */
    abstract protected function getRouteName();

    /**
     * @return string
     */
    abstract protected function getRouteParams();
}