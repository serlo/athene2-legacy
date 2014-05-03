<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Ui\View\Helper;

use Ui\Options\BrandHelperOptions;
use Zend\View\Helper\AbstractHelper;

class Brand extends AbstractHelper
{
    /**
     * @var BrandHelperOptions
     */
    protected $options;

    /**
     * @param BrandHelperOptions $brandHelperOptions
     */
    public function __construct(BrandHelperOptions $brandHelperOptions)
    {
        $this->options = $brandHelperOptions;
    }

    public function getLogo()
    {
        return $this->options->getLogo();
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param bool $stripTags
     * @return string
     */
    public function getBrand($stripTags = false)
    {
        if ($stripTags) {
            return strip_tags($this->options->getName());
        }

        return $this->options->getName();
    }

    /**
     * @param bool $stripTags
     * @return string
     */
    public function getSlogan($stripTags = false)
    {
        if ($stripTags) {
            return strip_tags($this->options->getSlogan());
        }

        return $this->options->getSlogan();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->options->getDescription();
    }
}
