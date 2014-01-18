<?php
namespace Ads\Manager;

use Language\Entity\LanguageInterface;
use Ads\Entity\AdInterface;

interface AdsManagerInterface
{
    /**
     *
     * @param numeric $id
     * @return AdInterface
      */
    public function getAd($id);
    /**
     *
     * @param array $data
     * @param AdInterface $ad
     * @return AdInterface
      */
    public function updateAd(array $data, AdInterface $ad);
     /**
     *
     * @param AdInterface $ad
     * @return this
      */
    public function removeAd(AdInterface $ad);
    /**
     *
     * @param LanguageInterface $language
     * @param numeric $number
     * @return array
     */
    public function findShuffledAds(LanguageInterface $language, $number);
    /**
     *
     * @param array $data
     * @return AdInterface
     */
    public function createAd(array $data);
    /**
     *
     * @param LanguageInterface $language
     * @return AdInterface
     */
    public function findAllAds(LanguageInterface $language);

}

