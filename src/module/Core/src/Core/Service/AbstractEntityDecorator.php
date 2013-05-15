<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Core\Service;

use Core\Structure\AbstractDecorator;
use Core\Entity\EntityInterface;

class AbstractEntityDecorator extends AbstractDecorator
{
    /**
     *
     * @var EntityInterface
     */
    protected $entity;

    /**
     *
     * @return \Core\Entity\EntityInterface $entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     *
     * @param \Core\Entity\EntityInterface $entity            
     * @return $this
     */
    public function setEntity(EntityInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }
}