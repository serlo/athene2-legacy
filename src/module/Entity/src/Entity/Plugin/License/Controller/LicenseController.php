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
namespace Entity\Plugin\License\Controller;

use Entity\Plugin\Controller\AbstractController;
use Entity\Plugin\License\Form\LicenseForm;
use Zend\View\Model\ViewModel;

class LicenseController extends AbstractController
{
    use\Language\Manager\LanguageManagerAwareTrait;

    public function updateAction()
    {
        $language = $this->getLanguageManager()->getLanguageFromRequest();
        $licenses = $this->getPlugin()->getLicenses($language);
        $form = new LicenseForm($licenses);
        $view = new ViewModel(array(
            'form' => $form
        ));
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $this->getPlugin()->setLicense((int) $data['license']);
                $this->getEntityManager()
                    ->getObjectManager()
                    ->flush();
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