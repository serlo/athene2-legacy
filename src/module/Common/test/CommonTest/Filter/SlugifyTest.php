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
namespace CommonTest\Filter;

use Common\Filter\Slugify;

class SlugifyTest extends \PHPUnit_Framework_TestCase
{

    public function slugifyProvider()
    {
        return [
            [
                'input' => 'slug',
                'output' => 'slug'
            ],
            [
                'input' => 'slug delimiter',
                'output' => 'slug-delimiter'
            ],
            [
                'input' => 'slug   delimiter',
                'output' => 'slug-delimiter'
            ],
            [
                'input' => 'slug delimiter.?!',
                'output' => 'slug-delimiter'
            ],
            [
                'input' => 'slug äöß',
                'output' => 'slug-aoss'
            ],
            [
                'input' => 'SlUg',
                'output' => 'slug'
            ], 
            [
                'input' => 'slug- -delimiter',
                'output' => 'slug-delimiter'
            ],           
        ];
    }

    /**
     * @dataProvider slugifyProvider
     */
    public function testFilter($input, $output)
    {
        $filter = new Slugify();
        $this->assertSame($output, $filter->filter($input));
    }
}