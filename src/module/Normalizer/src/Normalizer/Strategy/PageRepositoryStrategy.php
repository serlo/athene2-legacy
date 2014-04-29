<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
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

    public function isValid($object)
    {
        return $object instanceof PageRepositoryInterface;
    }

    protected function getContent()
    {
        $revision = $this->getRevision();
        if ($revision) {
            return $revision->getContent();
        }
        return '';
    }

    protected function getId()
    {
        return $this->getObject()->getId();
    }

    protected function getPreview()
    {
        $revision = $this->getRevision();
        if ($revision) {
            return $revision->getContent();
        }
        return '';
    }

    protected function getRevision()
    {
        $revision = $this->getObject()->getCurrentRevision();
        if (!$revision) {
            $revision = $this->getObject()->getRevisions()->current();
        }
        return $revision;
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

    protected function getTimestamp()
    {
        $revision = $this->getRevision();
        if ($revision) {
            return $revision->getDate();
        }
        return new \DateTime;
    }

    protected function getTitle()
    {
        $revision = $this->getRevision();
        if ($revision) {
            return $revision->getTitle();
        }
        return '';
    }

    protected function getType()
    {
        return 'Page repository';
    }
}