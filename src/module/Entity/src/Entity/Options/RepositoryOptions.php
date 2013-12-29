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
namespace Entity\Options;

use Zend\Stdlib\AbstractOptions;

class RepositoryOptions extends AbstractOptions
{

    /**
     *
     * @var string
     */
    protected $form;

    /**
     *
     * @var array
     */
    protected $fields = [];

    /**
     *
     * @return string $form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     *
     * @return array $fields
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     *
     * @param string $form            
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     *
     * @param array $fields            
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }
    
    public function isValid($key)
    {
        return $key == 'repository';
    }
}