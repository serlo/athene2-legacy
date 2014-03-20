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
use Attachment\Exception\NoFileSent;
use Instance\Manager\InstanceManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdsController extends AbstractActionController
{
    use InstanceManagerAwareTrait;
    use\Common\Traits\ObjectManagerAwareTrait;
    use\User\Manager\UserManagerAwareTrait;
    use\Ads\Manager\AdsManagerAwareTrait;
    use\Attachment\Manager\AttachmentManagerAwareTrait;

    public function indexAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $this->assertGranted('ad.get', $instance);
        $ads = $this->getAdsManager()->findAllAds($instance);
        $view = new ViewModel([
            'ads' => $ads
        ]);
        $view->setTemplate('ads/ads');
        
        return $view;
    }

    public function addAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $user = $this->getUserManager()->getUserFromAuthenticator();
        $form = new AdForm();
        $this->assertGranted('ad.create', $instance);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data = array_merge($data, $this->getRequest()
                ->getFiles()
                ->toArray());
            
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                $upload = $this->getAttachmentManager()->attach($form);
                $array = array_merge($array, [
                    'attachment' => $upload,
                    'author' => $user,
                    'instance' => $instance
                ]);
                
                $this->getAdsManager()->createAd($array);
                $this->getAdsManager()->flush();
                
                return $this->redirect()->toRoute('ads');
            }
        }
        
        $view = new ViewModel([
            'form' => $form
        ]);
        $view->setTemplate('ads/create');
        
        return $view;
    }

    public function deleteAction()
    {
        $id = $this->params('id');
        $ad = $this->getAdsManager()->getAd($id);
        $this->assertGranted('ad.remove', $ad);
        $this->getAdsManager()->removeAd($ad);
        $this->getAdsManager()->flush();
        
        return $this->redirect()->toRoute('ads');
    }
    
    /*
     * public function shuffleAction() { $instance = $this->getInstanceManager()->getInstanceFromRequest(); $this->assertGranted('ad.get', $instance); $ads = $this->getAdsManager()->findShuffledAds($instance, 3); $view = new ViewModel([ 'ads' => $ads ]); $view->setTemplate('ads/shuffle'); return $view; }
     */
    public function editAction()
    {
        $form = new AdForm();
        $id = $this->params('id');
        $ad = $this->getAdsManager()->getAd($id);
        $this->assertGranted('ad.update', $ad);
        
        // todo: use hydrator instead
        $form->get('content')->setValue($ad->getContent());
        $form->get('title')->setValue($ad->getTitle());
        $form->get('frequency')->setValue($ad->getFrequency());
        $form->get('url')->setValue($ad->getUrl());
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data = array_merge($data, $this->getRequest()
                ->getFiles()
                ->toArray());
            
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                
                // Try updating the upload
                try {
                    $upload = $this->getAttachmentManager()->attach($form);
                    $array['attachment'] = $upload;
                } catch (NoFileSent $e) {
                    // No file has been sent, so we stick to the old one
                    $array['attachment'] = $ad->getAttachment();
                }
                
                $this->getAdsManager()->updateAd($array, $ad);
                $this->getAdsManager()->flush();
                $this->redirect()->toRoute('ads');
            }
        }
        
        $view = new ViewModel([
            'form' => $form
        ]);
        $view->setTemplate('ads/update');
        
        return $view;
    }

    /*
    public function setAdPage(){
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $id = $this->params('id');
        $this->getAdsManager()->createAdPage()
        
    }*/

    public function adPageAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $this->assertGranted('ad.get', $instance);
        $adPage = $this->getAdsManager()->getAdPage($instance);
        if (!is_object($adPage)) {
            return $this->redirect()->toReferer();
        }
        
        $repository = $adPage->getPageRepository();
        return $this->redirect()->toRoute('page/view', [
            'page' => $repository
        ]);
    }

    public function outAction()
    {
        $id = $this->params('id');
        $ad = $this->getAdsManager()->getAd($id);
        $this->getAdsManager()->clickAd($ad);
        $this->getAdsManager()->flush();
        return $this->redirect()->toUrl($ad->getUrl());
    }
}
