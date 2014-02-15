<?php


namespace Page\Form;

use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class RepositoryForm extends Form
{
    protected $objectManager;

    public function __construct($entityManager)
    {
        parent::__construct('createPage');

        $this->objectManager = $entityManager;
        $filter              = new InputFilter();

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        $this->setInputFilter($filter);
        $this->add((new Text('slug'))->setLabel('Url:'));
        $this->add((new MultiCheckbox('roles'))->setValueOptions($this->findRolesArray())->setLabel('Roles:'));
        $this->add((new Submit('submit'))->setValue('Save')->setAttribute('class', 'btn btn-success pull-right'));
    }

    private function findRolesArray()
    {
        $repository = $this->objectManager->getRepository('User\Entity\Role');
        $roles      = $repository->findAll();
        $array      = array();
        foreach ($roles as $role) {
            $array[$role->getId()] = $role->getName();
        }

        return $array;
    }
}