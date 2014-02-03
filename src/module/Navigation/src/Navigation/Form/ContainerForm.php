<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Navigation\Form;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as ObjectHydrator;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ContainerForm extends Form
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('container');

        $hydrator = new ObjectHydrator($entityManager);
        $filter   = new InputFilter();

        $this->setHydrator($hydrator);
        $this->setInputFilter($filter);

        $this->add((new Hidden('type')));
        $this->add((new Hidden('instance')));

        $this->add(
            (new Submit('submit'))->setValue('Save')->setAttribute('class', 'btn btn-success pull-right')
        );

        $filter->add(
            [
                'name'     => 'type',
                'required' => true,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ]
        );

        $filter->add(
            [
                'name'     => 'instance',
                'required' => true,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ]
        );
    }
}
