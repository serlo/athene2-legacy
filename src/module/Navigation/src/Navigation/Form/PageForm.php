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
use Doctrine\ORM\Internal\Hydration\ObjectHydrator;
use Zend\Form\Element\Hidden;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class PageForm extends Form
{
    public function __construct(EntityManager $entityManager)
    {
        $hydrator = new ObjectHydrator($entityManager);
        $filter   = new InputFilter();

        $this->setHydrator($hydrator);
        $this->setInputFilter($filter);

        $this->add((new Hidden('instance')));
        $this->add((new Hidden('container')));

        $filter->add(
            [
                'name'     => 'container',
                'required' => true
            ]
        );
    }
}
