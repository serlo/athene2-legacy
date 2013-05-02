<?php
namespace Entity\Component;

use Zend\View\Model\ViewModel;

class RenderService
{
    protected $_viewClassName;
    
    function __construct ($template, array $data = null)
    {
        $view = new ViewModel($data);
        $view->setTemplate($template);
    	$this->_view = $view;
    }
    
    public function populate(array $data){
        foreach($data as $name => $value)
            $this->_view->setVariable($name, $value);
        
        return $this;        
    }
    
    public function render(){
        $this->_view;
    }
}