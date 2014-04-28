<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace RelatedContent\Entity;

abstract class AbstractType implements TypeInterface
{
    protected $id;

    public function getId()
    {
        return $this->id->getId();
    }

    public function getHolder()
    {
        return $this->id;
    }

    public function setHolder(HolderInterface $holder)
    {
        $this->id = $holder;
    }

    public function getContainer()
    {
        return $this->getContainer();
    }
}