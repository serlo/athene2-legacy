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
namespace TokenTest;

use Token\Tokenizer;
use TokenTest\Fake\ObjectFake;

/**
 * @codeCoverageIgnore
 */
class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    protected $tokenizer;
    
    public function setUp(){
        $this->tokenizer = new Tokenizer();
    }
    
    public function testTransliterate(){
        $provider = new Fake\ProviderFake();
        $object = new ObjectFake();
        $result = $this->tokenizer->transliterate($provider, $object, 'abc/{foo}-a-a-{id}');
        $this->assertEquals('abc/bar-a-a-1', $result);
    }

    /**
     * @expectedException \Token\Exception\RuntimeException
     */
    public function testTransliterateRuntimeException(){
        $provider = new Fake\ProviderFake();
        $object = new ObjectFake();
        $result = $this->tokenizer->transliterate($provider, $object, 'abc/{foo}-a-a-{id}-{error}');
        $this->assertEquals('abc/bar-a-a-1', $result);
    }
}