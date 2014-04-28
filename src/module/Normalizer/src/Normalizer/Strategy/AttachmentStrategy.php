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

use Attachment\Entity\AttachmentInterface;
use Attachment\Entity\ContainerInterface;

class AttachmentStrategy extends AbstractStrategy
{
    /**
     * @return ContainerInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    protected function getId()
    {
        return $this->getObject()->getId();
    }

    protected function getTitle()
    {
        return $this->getObject()->getFirstFile()->getFilename();
    }

    protected function getTimestamp()
    {
        return $this->getObject()->getFirstFile()->getDateTime();
    }

    protected function getContent()
    {
        return $this->getObject()->getFirstFile()->getLocation();
    }

    protected function getPreview()
    {
        return $this->getObject()->getFirstFile()->getLocation();
    }

    protected function getType()
    {
        return $this->getObject()->getType();
    }

    protected function getRouteName()
    {
        return 'attachment/info';
    }

    protected function getRouteParams()
    {
        return [
            'id' => $this->getObject()->getId()
        ];
    }

    public function isValid($object)
    {
        return $object instanceof ContainerInterface;
    }
}