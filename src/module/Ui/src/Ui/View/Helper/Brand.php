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
namespace Ui\View\Helper;

use Ui\Options\BrandHelperOptions;
use Zend\View\Helper\AbstractHelper;

class Brand extends AbstractHelper
{
    /**
     * @var \Ui\Options\BrandHelperOptions
     */
    protected $options;

    public function __construct(BrandHelperOptions $brandHelperOptions)
    {
        $this->options = $brandHelperOptions;
    }

    public function __invoke()
    {
        return $this;
    }

    public function getBrand($stripTags = false)
    {
        if ($stripTags) {
            return strip_tags($this->options->getName());
        }

        return $this->options->getName();
    }

    public function getSlogan($stripTags = false)
    {
        if ($stripTags) {
            return strip_tags($this->options->getSlogan());
        }

        return $this->options->getSlogan();
    }
}
