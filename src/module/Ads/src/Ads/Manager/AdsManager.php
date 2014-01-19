<?php

/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Jakob Pfab (jakob.pfab@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Ads\Manager;

use Ads\Entity\AdInterface;
use Ads\Exception\AdNotFoundException;
use Ads\Hydrator\AdHydrator;
use Language\Entity\LanguageInterface;
use Page\Exception\InvalidArgumentException;

class AdsManager implements AdsManagerInterface
{

    use \Common\Traits\ObjectManagerAwareTrait;
    use\ClassResolver\ClassResolverAwareTrait;
    use\Upload\Manager\UploadManagerAwareTrait;

    public function getAd($id)
    {
        if (!is_numeric($id)) {
            throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($id)));
        }

        $add = $this->getObjectManager()->find(
            $this->getClassResolver()->resolveClassName('Ads\Entity\AdInterface'),
            $id
        );
        if (!$add) {
            throw new AdNotFoundException(sprintf('%s', $id));
        }

        return $add;
    }

    protected function createAdEntity()
    {
        $ad = $this->getClassResolver()->resolve('Ads\Entity\AdInterface');

        return $ad;
    }

    public function updateAd(array $data, AdInterface $ad)
    {
        if (substr($data['url'], 0, 3) == 'www') {
            $data['url'] = 'http://' . $data['url'];
        }
        $hydrator = new AdHydrator();
        $hydrator->hydrate($data, $ad);
        $this->getObjectManager()->persist($ad);

        return $ad;
    }

    public function createAd(array $data)
    {
        if (substr($data['url'], 0, 3) == 'www') {
            $data['url'] = 'http://' . $data['url'];
        }
        $data['clicks'] = $data['views'] = 0;
        $ad             = $this->createAdEntity();
        $hydrator       = new AdHydrator();
        $hydrator->hydrate($data, $ad);
        $this->getObjectManager()->persist($ad);

        return $ad;
    }

    public function findAllAds(LanguageInterface $language)
    {
        $ads = $this->getObjectManager()->getRepository(
                $this->getClassResolver()->resolveClassName('Ads\Entity\AdInterface')
            )->findBy(
                array(
                    'language' => $language->getId()
                )
            );

        return $ads;
    }

    public function removeAd(AdInterface $ad)
    {
        $this->getObjectManager()->remove($ad);

        return this;
    }

    public function findShuffledAds(LanguageInterface $language, $number)
    {
        $allAds    = $this->findAllAds($language);
        $adsScaled = array();
        $ads       = array();
        if (count($allAds) < $number) {
            return $ads;
        }
        $numberAds = $y = 0;
        foreach ($allAds as $ad) {

            for ($i = 0; $i < $ad->getFrequency(); $i++) {
                $adsScaled[$numberAds + $i] = $y;
            }
            $numberAds = $numberAds + $ad->getFrequency();
            $y++;
        }

        $random = mt_rand(0, $numberAds - 1);

        for ($i = 0; $i < $number; $i++) {
            $random = mt_rand(0, $numberAds - 1);
            while (in_array($allAds[$adsScaled[$random]], $ads)) {
                $random = mt_rand(0, $numberAds - 1);
            }

            $ads[$i] = $allAds[$adsScaled[$random]];
        }

        foreach ($ads as $ad) {
            $ad->setViews($ad->getViews() + 1);
            $this->getObjectManager()->persist($ad);
        }

        return $ads;
    }
}