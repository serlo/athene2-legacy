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
namespace TaxonomyTest\Fake;

use Taxonomy\Entity\TaxonomyType;
class TaxonomyRepositoryFake extends \AtheneTest\Fake\EntityRepositoryFake
{

    protected $className = 'Taxonomy\Entity\Taxonomy';

    protected function getData ()
    {
        return array(
            array(
                'id' => 1,
                'type' => (new TaxonomyType())->setId(1)->setName('foobar'),
                'language' => 1
            ),
            array(
                'id' => 2,
                'type' => (new TaxonomyType())->setId(2)->setName('foobar'),
                'language' => 1
            ),
            array(
                'id' => 3,
                'type' => (new TaxonomyType())->setId(3)->setName('foobar'),
                'language' => 1
            )
        );
    }
}