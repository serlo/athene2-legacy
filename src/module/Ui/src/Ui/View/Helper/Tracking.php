<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Ui\View\Helper;

use Ui\Options\TrackingHelperOptions;
use Zend\View\Helper\AbstractHelper;

class Tracking extends AbstractHelper
{
    /**
     * @var TrackingHelperOptions
     */
    protected $options;

    /**
     * @param TrackingHelperOptions $options
     */
    public function __construct(TrackingHelperOptions $options)
    {
        $this->options = $options;
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->options->getCode();
    }
}
 