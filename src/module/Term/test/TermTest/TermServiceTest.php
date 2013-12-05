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
use Language\Entity\LanguageEntity;
use Term\Service\TermService;

/**
 * @codeCoverageIgnore
 */
class TermServiceTest extends Model
{

    public function getData()
    {
        return array(
            'name' => 'asdf',
            'slug' => 'asdf',
            'language' => new Language(),
            'id' => NULL,
            'manager' => $this->getMock('Term\Manager\TermManager')
        );
    }

    public function setUp()
    {
        parent::setUp();
        $termService = new TermService();
        $termService->setEntity(new Term());
        $this->setObject($termService);
    }
}