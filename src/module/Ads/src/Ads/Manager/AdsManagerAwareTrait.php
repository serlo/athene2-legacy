<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Jakob Pfab (jakob.pfab@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */

namespace Ads\Manager;

trait AdsManagerAwareTrait
{

    /**
     * @var AdsManagerInterface
     */
    protected $adsManager;

    /**
     * @return AdsManagerInterface
     */
    public function getAdsManager()
    {
        return $this->adsManager;
    }

    /**
     * @param AdsManagerInterface $adsManager
     * @return $this
     */
    public function setAdsManager(AdsManagerInterface $adsManager)
    {
        $this->adsManager = $adsManager;

        return $this;
    }
}