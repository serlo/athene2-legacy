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

    /**
     *
     * @var Form
     */
    protected $_form;
    
    /**
     * The default template for the View
     * 
     * @var string
     */
    protected $template = 'entity/learning-objects/core/default';
    
    /**
     * @var ViewModel
     */
    protected $_viewModel;

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
        
        return $this->_form;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Entity\Factory\EntityBuilderInterface::hasForm()
     */
    public function hasForm ()
    {
        return $this->_form !== NULL;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Entity\Factory\EntityBuilderInterface::setForm()
     */
    public function setForm (Form $_form)
    {
        $this->_form = $_form;
        return $this;
    }

    /**
     * Returns an array with data for the ViewModel
     *
     * @return array
     */
   /* abstract public function getData ();

    abstract public function getFormData ();

    abstract public function getFormObject ();*/
}