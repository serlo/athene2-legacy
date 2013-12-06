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
namespace TaxonomyTest;

use AtheneTest\TestCase\Model;
use Taxonomy\Entity\Taxonomy;
use Language\Entity\LanguageEntity;
use Taxonomy\Entity\TaxonomyType;
use Doctrine\Common\Collections\ArrayCollection;

class TaxonomyTypeTest extends Model
{

    public function setUp()
    {
        $this->setObject(new TaxonomyType());
    }
    
    /*
     * (non-PHPdoc) @see \AtheneTest\TestCase\Model::getData()
     */
    protected function getData()
    {
        return array(
            'id' => NULL,
            'name' => 'a',
            'taxonomies' => new ArrayCollection()
        );
    }
}
