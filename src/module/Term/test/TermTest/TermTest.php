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
namespace TermTest;

use AtheneTest\TestCase\Model;
use Term\Entity\Term;
use Language\Entity\Language;

/**
 * @codeCoverageIgnore
 */
class TermTest extends Model
{

    public function getData()
    {
        return array(
            'id' => 1234,
            'name' => 'asdf',
            'slug' => 'asdf',
            'language' => new Language()
        );
    }

    public function setUp()
    {
        parent::setUp();
        
        $this->setObject(new Term());
    }
}