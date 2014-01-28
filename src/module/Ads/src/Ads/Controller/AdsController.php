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
namespace Ads\Controller;

use Ads\Form\AdForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdsController extends AbstractActionController
{
    use\Language\Manager\LanguageManagerAwareTrait;
    use\Common\Traits\ObjectManagerAwareTrait;
    use\User\Manager\UserManagerAwareTrait;
    use\Ads\Manager\AdsManagerAwareTrait;
    use\Attachment\Manager\AttachmentManagerAwareTrait;

    public function indexAction()
    {
        $ads = $this->getAdsManager()->findAllAds($this->getLanguageManager()
            ->getLanguageFromRequest());
        $view = new ViewModel(array(
            'ads' => $ads
        ));
        $view->setTemplate('ads/ads');
        
        return $view;
    }

    public function addAction()
    {
        $user = $this->getUserManager()->getUserFromAuthenticator();
        $form = new AdForm();
        $language = $this->getLanguageManager()->getLanguageFromRequest();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data = array_merge($data, $this->getRequest()
                ->getFiles()
                ->toArray());
            
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                
                $upload = $this->getAttachmentManager()->attach($array['file']);
                
                $array['attachment'] = $upload;
                $array['author'] = $user;
                $array['language'] = $language;
                
                $this->getAdsManager()->createAd($array);
                
                $this->getObjectManager()->flush();
                
                $this->redirect()->toRoute('ads');
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form,
            'title' => 'Ad erstellen'
        ));
        $view->setTemplate('ads/form.phtml');
        
        return $view;
    }

    public function deleteAction()
    {
        $id = $this->params('id');
        $ad = $this->getAdsManager()->getAd($id);
        $this->getAdsManager()->removeAd($ad);
        $this->getObjectManager()->flush();
        $this->redirect()->toRoute('ads');
    }

    public function shuffleAction()
    {
        $ads = $this->getAdsManager()->findShuffledAds($this->getLanguageManager()
            ->getLanguageFromRequest(), 3);
        $view = new ViewModel(array(
            'ads' => $ads
        ));
        $view->setTemplate('ads/shuffle.phtml');
        $this->getObjectManager()->flush();
        
        return $view;
    }

    public function editAction()
    {
        $form = new AdForm();
        $id = $this->params('id');
        $language = $this->getLanguageManager();
        $ad = $this->getAdsManager()->getAd($id);
        
        $form->get('content')->setValue($ad->getContent());
        $form->get('title')->setValue($ad->getTitle());
        $form->get('frequency')->setValue($ad->getFrequency());
        $form->get('file')->setAttribute('required', false);
        $form->get('file')->setLabel('Edit Image');
        $form->get('url')->setValue($ad->getUrl());
        
        $ad = $this->getAdsManager()->getAd($id);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            
            $data = array_merge($data, $this->getRequest()
                ->getFiles()
                ->toArray());
            
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                
                if ($array['file']['error']==0) {
                 
                    $upload = $this->getAttachmentManager()->attach($array['file']);
                    $array['attachment'] = $upload;
                }
                $this->getAdsManager()->updateAd($array, $ad);
                $this->getObjectManager()->flush();
                $this->redirect()->toRoute('ads');
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form,
            'title' => 'Ad bearbeiten'
        ));
        $view->setTemplate('ads/form.phtml');
        
        return $view;
    }

    public function outAction()
    {
        $id = $this->params('id');
        $ad = $this->getAdsManager()->getAd($id);
        $ad->setClicks($ad->getClicks() + 1);
        $this->getObjectManager()->persist($ad);
        $this->getObjectManager()->flush();
        $this->redirect()->toUrl($ad->getUrl());
    }
}
