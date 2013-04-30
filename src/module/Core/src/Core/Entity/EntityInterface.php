<?php
namespace Core\Entity;

use Core\Entity\ModelInterface;
use Core\Exception\UnstatisfiedException;

interface EntityInterface extends ModelInterface
{
    
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy ();
    
    /**
     * @return array
     */
    public function toArray();

    /**
     * does the associated element exist?
     * 
     * @return boolean
     */
    public function exists ($association);
    
    /**
     * populates the entity
     * 
     * @throws UnstatisfiedException
     * @param array $data
     * @return $this
     */
    public function populate(array $data);
}