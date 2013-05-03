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
use Entity\Exception\TemplateNotSetException;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ModelInterface;

abstract class AbstractEntityBuilder extends EntityServiceProxy implements EntityBuilderInterface
{
    
    /**
     * Templates for ViewModels
     * 
     * @var array
     */
    protected $_templates = array();
    
    /**
     * @var Form
     */
    protected $_form;
    
    /**
     * @var ViewModel
     */
    protected $_viewModel;
    
    /**
     * @var JsonModel
     */
    protected $_jsonModel;
    
    /**
     * (non-PHPdoc)
     * @see \Entity\Factory\EntityBuilderInterface::getJsonModel()
     */
    public function getJsonModel ()
    {
        return $this->_jsonModel;
    }

	/**
	 * (non-PHPdoc)
	 * @see \Entity\Factory\EntityBuilderInterface::setJsonModel()
	 */
    public function setJsonModel (JsonModel $_jsonModel)
    {
        $this->_jsonModel = $_jsonModel;
        return $this;
    }

	/**
	 * (non-PHPdoc)
	 * @see \Entity\Factory\EntityBuilderInterface::getViewModel()
	 */
    public function getViewModel ()
    {
        return $this->_viewModel;
    }

	/**
	 * (non-PHPdoc)
	 * @see \Entity\Factory\EntityBuilderInterface::setViewModel()
	 */
    public function setViewModel (ViewModel $_viewModel)
    {
        $this->_viewModel = $_viewModel;
        return $this;
    }

	/**
	 * (non-PHPdoc)
	 * @see \Entity\Factory\EntityBuilderInterface::getTemplate()
	 */
    public function getTemplate ($name)
    {
        return $this->_templates[$name];
    }

	/**
	 * (non-PHPdoc)
	 * @see \Entity\Factory\EntityBuilderInterface::getForm()
	 */
    public function getForm ()
    {
        return $this->_form;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Entity\Factory\EntityBuilderInterface::hasForm()
     */
    public function hasForm(){
        return $this->_form !== NULL;
    }

    /**
     * (non-PHPdoc)
     * @see \Entity\Factory\EntityBuilderInterface::hasTemplate()
     */
    public function hasTemplate($name){
        return isset($this->_templates[$name]) && $this->_templates[$name] !== NULL;
    }

	/**
	 * (non-PHPdoc)
	 * @see \Entity\Factory\EntityBuilderInterface::setTemplate()
	 */
    public function setTemplate ($name, $template)
    {
        $this->_templates[$name] = $template;
        return $this;
    }

	/**
	 * (non-PHPdoc)
	 * @see \Entity\Factory\EntityBuilderInterface::setForm()
	 */
    public function setForm (Form $_form)
    {
        $this->_form = $_form;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Entity\Factory\EntityBuilderInterface::build()
     */
    public function build (EntityFactoryInterface $adaptee)
    {
        $this->setSource($adaptee);
        
        $this->uniqueName = 'Entity(' . $adaptee->getId() . ')';
        $this->_loadComponents();
        $this->_viewModel = new ViewModel();
        return $this;
    }

    /**
     * Loads the components for the Learning Object
     * 
     * @return $this;
     */
    abstract protected function _loadComponents ();
    
    /**
     * Returns an array with data for the ViewModel
     * 
     * @return array
     */
    abstract protected function _getViewModelData();
    
    abstract protected function _getFormData();
    
    abstract protected function _getJsonModelData();
    
    /**
     * Populates a model
     * 
     * @param ModelInterface $model
     * @param array $data
     * @return $this
     */
    private function _populateModel(ModelInterface $model, array $data){
        foreach($data as $name => $value){
            if($value instanceof $model){
                $this->getViewModel()->addChild($value, $name);
            } else {
                $this->getViewModel()->setVariable($name, $value);             
            }
        }
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Entity\Factory\EntityBuilderInterface::toViewModel()
     */
    public function toViewModel($name){
        if(!$this->hasTemplate($name))
            throw new TemplateNotSetException('Template for `'.$name.'` not set.');
        
        if($this->hasForm()){
            $this->getForm()->setData($this->_getFormData());
            $this->getViewModel()->setVariable('form', $this->getForm());
        }
        
        $this->_populateModel($this->getViewModel(), $this->_getViewModelData());
        return $this->getViewModel()->setTemplate($this->getTemplate($name));
    }
    
    /**
     * (non-PHPdoc)
     * @see \Entity\Factory\EntityBuilderInterface::toJsonModel()
     */
    public function toJsonModel(){
        $this->_populateModel($this->getJsonModel(), $this->_getJsonModelData());
        return $this->getJsonModel();
    }
}