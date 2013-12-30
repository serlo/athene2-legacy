<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author  Jakob Pfab (jakob.pfab@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Ads\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ads\Form\AdForm;
use Zend\Form\Form;

class AdsController extends AbstractActionController
{
    use\Language\Manager\LanguageManagerAwareTrait;
    use\Common\Traits\ObjectManagerAwareTrait;
    use\User\Manager\UserManagerAwareTrait;
    use\Ads\Manager\AdsManagerAwareTrait;
    use\Upload\Manager\UploadManagerAwareTrait;

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
                
                $upload = $this->getUploadManager()->upload($array['file']);
                
                $array['image'] = $upload;
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
}
