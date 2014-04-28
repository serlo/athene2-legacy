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

use User\Entity\UserInterface;

class UserStrategy extends AbstractStrategy
{

    /**
     * @return UserInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    public function isValid($object)
    {
        return $object instanceof UserInterface;
    }

    protected function getContent()
    {
        return $this->getObject()->getUsername();
    }

    protected function getId()
    {
        return $this->getObject()->getId();
    }

    protected function getPreview()
    {
        return $this->getObject()->getUsername();
    }

    protected function getRouteName()
    {
        return 'user/profile';
    }

    protected function getRouteParams()
    {
        return ['id' => $this->getObject()->getId()];
    }

    protected function getTimestamp()
    {
        return $this->getObject()->getDate();
    }

    protected function getTitle()
    {
        return $this->getObject()->getUsername();
    }

    protected function getType()
    {
        return 'user';
    }
}