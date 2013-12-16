<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Controller;

use Zend\View\Model\ViewModel;
use License\Form\UpdateLicenseForm;

class LicenseController extends AbstractController
{
    use \Language\Manager\LanguageManagerAwareTrait,\License\Manager\LicenseManagerAwareTrait;

    public function updateAction()
    {
        $language = $this->getLanguageManager()->getLanguageFromRequest();
        $licenses = $this->getLicenseManager()->findLicensesByLanguage($language);
        $entity = $this->getEntity();
        
        $form = new UpdateLicenseForm($licenses);
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                
                $this->getLicenseManager()->injectLicense($entity, (int) $data['license']);
                $this->getLicenseManager()->flush();
                
                $this->redirect()->toUrl($this->referer()
                    ->fromStorage());
            }
        } else {
            $this->referer()->store();
        }
        
        $view->setTemplate('entity/plugin/license/update');
        return $view;
    }
}