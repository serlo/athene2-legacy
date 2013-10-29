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
namespace Common\Model;

class EntityModel implements EntityModelInterface
{

    /**
     *
     * @var object
     */
    protected $entity;

    /**
     *
     * @return object $entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     *
     * @param object $entity            
     * @return $this
     */
    public function setEntity($entity)
    {
        if (! is_object($entity))
            throw new \InvalidArgumentException(sprintf('Expected object but got `%s`', gettype($entity)));
        
        $this->entity = $entity;
        return $this;
    }
}