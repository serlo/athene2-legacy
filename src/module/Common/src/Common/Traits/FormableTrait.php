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
namespace Common\Traits;

trait FormableTrait
{

    /**
     *
     * @var \Zend\Form\FormForm
     */
    protected $form;

    /**
     *
     * @return \Zend\Form\Form $form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     *
     * @param \Zend\Form\Form $form            
     * @return self
     */
    public function setForm(\Zend\Form\Form $form)
    {
        $this->form = $form;
        return $this;
    }
}