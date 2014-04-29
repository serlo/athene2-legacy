<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Ads\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Horizon extends AbstractHelper
{
    use \Ads\Manager\AdsManagerAwareTrait;
    use \Instance\Manager\InstanceManagerAwareTrait;

    protected $ads;

    public function __invoke($number)
    {
        $instance  = $this->getInstanceManager()->getInstanceFromRequest();
        $this->ads = $this->getAdsManager()->findShuffledAds($instance, $number);

        return $this->getView()->partial(
            'ads/helper/ads-helper',
            [
                'ads' => $this->ads,
            ]
        );
    }
}
