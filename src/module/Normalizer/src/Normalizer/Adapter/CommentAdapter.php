<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Normalizer\Adapter;

use Discussion\Entity\CommentInterface;

class CommentAdapter extends AbstractAdapter
{
    /**
     * @return CommentInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    public function isValid($object)
    {
        return $object instanceof CommentInterface;
    }

    protected function getContent()
    {
        return $this->getObject()->getContent();
    }

    protected function getId()
    {
        return $this->getObject()->getId();
    }

    protected function getKeywords()
    {
        return [];
    }

    protected function getPreview()
    {
        return $this->getContent();
    }

    protected function getRouteName()
    {
        return 'discussion/view';
    }

    protected function getRouteParams()
    {
        return [
            'id' => $this->getObject()->hasParent() ? $this->getObject()->getParent()->getId() :
                    $this->getObject()->getId()
        ];
    }

    protected function getCreationDate()
    {
        return $this->getObject()->getTimestamp();
    }

    protected function getTitle()
    {
        return $this->getObject()->hasParent() ? $this->getObject()->getParent()->getTitle() :
            $this->getObject()->getTitle();
    }

    protected function getType()
    {
        return $this->getObject()->hasParent() ? 'comment' : 'parent';
    }
}
