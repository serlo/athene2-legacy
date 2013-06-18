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

abstract class AbstractEntity extends GraphDecorator
{
    protected $controller;
    
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
    
    /**
     *
     * @var Form
     */
    protected $form;
    
    /**
     * The default template for the View
     * 
     * @var string
     */
    protected $template = 'entity/learning-objects/core/default';
    
    /**
     * @var ViewModel
     */
    protected $viewModel;

	/**
     * @return string $template
     */
    public function getTemplate ()
    {
        return $this->template;
    }

	/**
     * @param string $template
     * @return $this
     */
    public function setTemplate ($template)
    {
        $this->template = $template;
        return $this;
    }

	/**
     * (non-PHPdoc)
     *
     * @see \Entity\Factory\EntityBuilderInterface::getForm()
     */
    public function getForm ()
    {
        if (! $this->hasForm()) {
            $form = $this->getFormObject();
            $form->setData($this->getFormData());
            $this->setForm($form);
        }
        
        return $this->form;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Entity\Factory\EntityBuilderInterface::hasForm()
     */
    public function hasForm ()
    {
        return $this->form !== NULL;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Entity\Factory\EntityBuilderInterface::setForm()
     */
    public function setForm (Form $form)
    {
        $this->form = $form;
        return $this;
    }
}