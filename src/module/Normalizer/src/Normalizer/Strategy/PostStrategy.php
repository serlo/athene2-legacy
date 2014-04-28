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

use Blog\Entity\PostInterface;

class PostStrategy extends AbstractStrategy
{

    /**
     * @return PostInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    public function isValid($object)
    {
        return $object instanceof PostInterface;
    }

    protected function getContent()
    {
        return $this->getObject()->getContent();
    }

    protected function getId()
    {
        return $this->getObject()->getId();
    }

    protected function getPreview()
    {
        return $this->getObject()->getContent();
    }

    protected function getRouteName()
    {
        return 'blog/post/view';
    }

    protected function getRouteParams()
    {
        return [
            'post' => $this->getObject()->getId()
        ];
    }

    protected function getTimestamp()
    {
        return $this->getObject()->getTimestamp();
    }

    protected function getTitle()
    {
        return $this->getObject()->getTitle();
    }

    protected function getType()
    {
        return 'blogPost';
    }
}