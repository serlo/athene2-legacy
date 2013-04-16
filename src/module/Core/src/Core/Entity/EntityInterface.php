<?php
namespace Core\Entity;

use Core\Entity\ModelInterface;

interface EntityInterface extends ModelInterface
{
    
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy ();

    /**
     * does the associated element exist?
     * 
     * @return boolean
     */
    public function exists ($association);
    
    /**
     * @return int
     */
    public function getId();
    
    /**
     * populates the entity
     * 
     * @param array $data
     * @return $this
     */
    public function populate(array $data);
}

?>