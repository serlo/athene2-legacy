<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Taxonomy\Form;

use Taxonomy\Hydrator\TaxonomyTermHydrator;
use Term\Form\TermFieldset;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class TermForm extends Form
{

    function __construct(TaxonomyTermHydrator $taxonomyTermHydrator)
    {
        parent::__construct('taxonomyTerm');
        $this->setAttribute('method', 'post');
        $filter = new InputFilter();
        $this->setInputFilter($filter);
        $this->setHydrator($taxonomyTermHydrator);

        $this->add(
            array(
                'name'       => 'parent',
                'attributes' => array(
                    'type' => 'hidden'
                ),
            )
        );

        $this->add(
            array(
                'name'       => 'position',
                'attributes' => array(
                    'type' => 'hidden'
                ),
            )
        );

        $this->add(
            array(
                'name'       => 'taxonomy',
                'attributes' => array(
                    'type' => 'hidden'
                ),
            )
        );

        $this->add(new TermFieldset());

        $this->add((new Textarea('description'))->setLabel('description:'));

        $this->add(
            (new Submit('submit'))->setValue('Save')->setAttribute('class', 'btn btn-success pull-right')
        );

        $filter->add(
            array(
                'name'     => 'description',
                'required' => false,
                'filters'  => array(
                    array(
                        'name' => 'HtmlEntities'
                    )
                )
            )
        );
        $filter->add(
            array(
                'name'     => 'taxonomy',
                'required' => true
            )
        );
    }
}