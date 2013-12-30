<?php

/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Jakob Pfab (jakob.pfab@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Ads\Manager;

use Ads\Manager\AdsManagerInterface;
use Ads\Entity\AdInterface;
use Ads\Exception\AdNotFoundException;
use Page\Exception\InvalidArgumentException;
use Language\Entity\LanguageInterface;

class AdsManager implements AdsManagerInterface
{
    
    use\Common\Traits\ObjectManagerAwareTrait;
    use \ClassResolver\ClassResolverAwareTrait;
    use \Upload\Manager\UploadManagerAwareTrait;

    public function getAd($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($id)));
        $add = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Ads\Entity\AdInterface'), $id);
        if (! $add)
            throw new AdNotFoundException(sprintf('%s', $id));
            return $add;
    }


    protected function createAdEntity()
    {
        $ad = $this->getClassResolver()->resolve('Ads\Entity\AdInterface');
        return $ad;
    }

    public function editAd(array $data, AdInterface $add)
    {
        $add->populate($data);
        $this->getObjectManager()->persist($add);
        return $add;
    }

    public function createAd(array $data)
    {   $data['clicks']=$data['views']=0;
        $ad = $this->createAdEntity();
        $ad->populate($data);
        $this->getObjectManager()->persist($ad);
        return $ad;
    }
    
    public function findAllAds(LanguageInterface $language)
    {
        $ads = $this->getObjectManager()
        ->getRepository($this->getClassResolver()
            ->resolveClassName('Ads\Entity\AdInterface'))
            ->findBy(array(
                'language' => $language->getId()
            ));
          
            return $ads;
    }
    

 
}