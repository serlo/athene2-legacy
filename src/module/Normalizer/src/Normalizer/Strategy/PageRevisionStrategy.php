<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @author      Jakob Pfab
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Normalizer\Strategy;

use Page\Entity\PageRevision;
use Page\Entity\PageRevisionInterface;

class PageRevisionStrategy extends AbstractStrategy
{

    /**
     * @return PageRevision
     */
    public function getObject()
    {
        return $this->object;
    }

    protected function getTitle()
    {
        return $this->getObject()->getTitle();
    }

    protected function getTimestamp()
    {
        return $this->getObject()->getDate();
    }

    protected function getContent()
    {
        return $this->getObject()->getContent();
    }

    protected function getPreview()
    {
        return $this->getObject()->getContent();
    }

    protected function getType()
    {
        return 'Page revision';
    }

    protected function getRouteName()
    {
        return 'page/revision/view';
    }

    protected function getRouteParams()
    {
        return array(
            'revision' => $this->getObject()->getId()
        );
    }

    public function isValid($object)
    {
        return $object instanceof PageRevisionInterface;
    }
}