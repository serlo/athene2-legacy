<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Jakob Pfab (jakob.pfab@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */

namespace Ads\Manager;

use Ads\Manager\AdsManagerInterface;
trait AdsManagerAwareTrait
{

    /**
     *
     * @var AdsManagerInterface
     */
    protected $adsManager;

    /**
     *
     * @return \Ads\Manager\AdsManagerInterface
     *         $pageManager
     */
    public function getAdsManager ()
    {
        return $this->adsManager;
    }

    /**
     *
     * @param \Ads\Manager\AdsManagerInterface $adsManager            
     * @return $this
     */
    public function setAdsManager (AdsManagerInterface $adsManager)
    {
        $this->adsManager = $adsManager;
        return $this;
    }
}