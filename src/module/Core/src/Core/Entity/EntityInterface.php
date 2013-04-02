<?php
namespace Core\Entity;

interface EntityInterface
{
    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property            
     * @return mixed          
     */
    public function get($property);

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property            
     * @param mixed $value            
     * @return $this
     */    
    public function set($property, $value);
    
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
}

?>