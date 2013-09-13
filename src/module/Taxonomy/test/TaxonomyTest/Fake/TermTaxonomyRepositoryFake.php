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

use Term\Entity\Term;

class TermTaxonomyRepositoryFake extends \AtheneTest\Fake\EntityRepositoryFake
{

    protected $className = 'Taxonomy\Entity\TermTaxonomy';
    
    protected function getData ()
    {
        return array(
            array(
                'id' => 1,
                'type' => 1,
                'term' => (new Term())->setId(1)->setName('footerm1')
            ),
            array(
                'id' => 2,
                'type' => 2,
                'term' => (new Term())->setId(2)->setName('footerm2')
            ),
            array(
                'id' => 3,
                'type' => 1,
                'term' => (new Term())->setId(3)->setName('footerm3')
            )
        );
    }
}