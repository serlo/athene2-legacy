<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace User\Form;

use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class Register extends Form
{

    public function __construct($entityManager)
    {
        parent::__construct('signUp');

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $filter = new RegisterFilter($entityManager);
        $this->setInputFilter($filter);

        $this->add((new Text('username'))->setLabel('Username:'));
        $this->add((new Email('email'))->setLabel('Email:'));
        $this->add((new Email('emailConfirm'))->setLabel('Confirm email:'));
        $this->add((new Password('password'))->setLabel('Password:'));
        $this->add((new Password('passwordConfirm'))->setLabel('Confirm password:'));
        $this->add((new Checkbox('tos'))->setLabel('I\'ve read and understood the terms of service.'));

        $this->add(
            (new Submit('submit'))->setValue('Sign up')->setAttribute('class', 'btn btn-success pull-right')
        );
    }
}
