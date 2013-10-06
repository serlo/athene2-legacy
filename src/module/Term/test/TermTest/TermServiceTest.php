<?php
namespace TermTest;

use AtheneTest\TestCase\Model;
use Term\Entity\Term;
use Language\Entity\Language;
use Term\Service\TermService;

class TermServiceTest extends Model
{

    public function getData()
    {
        return array(
            'name' => 'asdf',
            'slug' => 'asdf',
            'language' => new Language(),
            'id' => NULL,
            'termManager' => $this->getMock('Term\Manager\TermManager')
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