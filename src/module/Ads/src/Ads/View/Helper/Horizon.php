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
namespace Ads\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Horizon extends AbstractHelper
{

    use \Ads\Manager\AdsManagerAwareTrait;
    use \Language\Manager\LanguageManagerAwareTrait;

    protected $ads;


    public function __invoke($number)
    {
        $language  = $this->getLanguageManager()->getLanguageFromRequest();
        $this->ads = $this->getAdsManager()->findShuffledAds($language, $number);

        return $this->getView()->partial(
            'ads/helper/ads-helper',
            array(
                'ads' => $this->ads,
            )
        );
    }

    /* public function render(){
         return $this->getView()->partial('ads/helper/ads-helper', array(
             'ads' => $this->ads,
         ));
     }*/
}