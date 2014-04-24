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
namespace RelatedContent\Result;

use RelatedContent\Entity\InternalInterface;
use RelatedContent\Entity\TypeInterface;
use RelatedContent\Exception;

class InternalResult extends AbstractResult
{
    use\Common\Traits\RouterAwareTrait;

    /**
     * @return InternalInterface
     */
    public function getObject()
    {
        return parent::getObject();
    }

    public function setObject(TypeInterface $object)
    {
        if (!$object instanceof InternalInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected InternalInterface but got `%s`',
                get_class($object)
            ));
        }

        return parent::setObject($object);
    }

    public function getType()
    {
        return 'internal';
    }

    public function getTitle()
    {
        return $this->getObject()->getTitle();
    }

    public function getUrl()
    {
        return $this->getRouter()->assemble(
            [
                'object' => $this->getObject()->getReference()->getId()
            ],
            [
                'name' => 'get/uuid'
            ]
        );
    }
}
