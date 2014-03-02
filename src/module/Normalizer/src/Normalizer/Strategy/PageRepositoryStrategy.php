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

use Page\Entity\PageRepository;
use Page\Entity\PageRepositoryInterface;

class PageRepositoryStrategy extends AbstractStrategy
{

    /**
     * @return PageRepository
     */
    public function getObject()
    {
        return $this->object;
    }

    protected function getTitle()
    {
        return $this->getObject()->getCurrentRevision()->getTitle();
    }

    protected function getTimestamp()
    {
        return $this->getObject()->getCurrentRevision()->getDate();
    }

    protected function getContent()
    {
        return $this->getObject()->getCurrentRevision()->getContent();
    }

    protected function getPreview()
    {
        return $this->getObject()->getCurrentRevision()->getContent();
    }

    protected function getType()
    {
        return 'Page repository';
    }

    protected function getRouteName()
    {
        return 'page/view';
    }

    protected function getRouteParams()
    {
        return [
            'page' => $this->getObject()->getId()
        ];
    }

    public function isValid($object)
    {
        return $object instanceof PageRepositoryInterface;
    }
}