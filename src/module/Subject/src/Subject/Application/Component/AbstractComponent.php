<?php
namespace Subject\Application\Component;

abstract class AbstractComponent
{
    protected $identity;
    
    public function identify(){
        if(!$this->identity)
            $this->identity = uniqid();
        
        return $this->identity;
    }

    public function providesMethod ($method)
    {
        return method_exists($this, $method);
    }
}