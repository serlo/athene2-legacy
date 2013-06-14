<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Application\Taxonomy;

use Core\Structure\GraphDecorator;
use Taxonomy\Service\TermServiceInterface;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;

class Term extends GraphDecorator implements TermServiceInterface
{
    /**
     * @var string
     */
    protected $template;
    
    /**
     * @var Form
     */
    protected $form;
    
    /**
     * @return \Zend\Form\Form $form
     */
    public function getForm ()
    {
        $form = $this->form;
        $form->setData($this->toArray());
        return $form;
    }

	/**
     * @param \Zend\Form\Form $form
     * @return $this
     */
    public function setForm ($form)
    {
        $this->form = $form;
        return $this;
    }

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
     * 
     * @return \Zend\View\Helper\ViewModel
     */
	public function render(){
        $view = new ViewModel(array(
            'term' => $this,
            'children' => $this->getChildren()
        ));
        $view->setTemplate($this->template);
        return $view;
    }
}