<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\Factory;

use Zend\Form\Form;
use Zend\View\Model\ViewModel;
use Core\Structure\GraphDecorator;
use Zend\Mvc\Controller\AbstractActionController;
use Entity\Exception\InvalidArgumentException;

abstract class AbstractEntity extends GraphDecorator
{
    /**
     * @var AbstractActionController
     */
    protected $controller;
    
    /**
     * @var Form
     */
    protected $form;
    
    /**
     * @var string
     */
    protected $template;
    
    /**
     * @var ViewModel
     */
    protected $viewModel;

    
    public function getTemplate ()
    {
        return $this->template;
    }
    
    public function setTemplate ($template)
    {
        $this->template = $template;
        return $this;
    }
    
    public function getForm ()
    {
        if (! $this->hasForm()) {
            $form = $this->getFormObject();
            $form->setData($this->getFormData());
            $this->setForm($form);
        }
        
        return $this->form;
    }
    
    public function hasForm ()
    {
        return $this->form !== NULL;
    }

    public function setForm (Form $form)
    {
        $this->form = $form;
        return $this;
    }
    
    
    public function setController($controller){
        $this->controller = $controller;
        return $this;
    }
    
    public function getController(){
        return $this->controller;
    }
    
    public function getRoute(){
        return $this->controller->getRoute();
    }
    
    public function getViewModel($methodName){
        if(!method_exists($this->getController(), $methodName))
            throw new InvalidArgumentException('Controller of class `' . get_class($this->getController()). '` does not know a method called `' . $methodName . '`');
            
        return $this->getController()->$methodName($this->getId());
    }
}