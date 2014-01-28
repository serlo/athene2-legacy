<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author         Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license        LGPL-3.0
 * @license        http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link           https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright      Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Common\Filter;

use Zend\Filter\FilterInterface;
use Zend\Filter\StripTags;

class PreviewFilter implements FilterInterface
{

    /**
     * @var string
     */
    protected $append;

    /**
     * @var int
     */
    protected $length;

    public function __construct($length = 150, $append = '...')
    {
        $this->length = $length;
        $this->append = $append;
    }

    public function filter($value)
    {
        $appendLength = strlen($this->append);
        $length       = $this->length - $appendLength;
        $stripTags    = new StripTags();
        $value        = $stripTags->filter($value);

        if (strlen($value) > $length) {
            $value = substr($value, 0, $length) . $this->append;
        }

        return $value;
    }
}