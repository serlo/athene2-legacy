<?php
namespace Core\Entity;

interface ModelInterface
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
}