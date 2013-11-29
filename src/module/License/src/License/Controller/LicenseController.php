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
namespace License\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class LicenseController extends AbstractActionController
{
    use\License\Manager\LicenseManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    public function manageAction()
    {
        $languageService = $this->getLanguageManager()->getLanguageFromRequest();
        $view = new ViewModel(array(
            'licenses' => $this->getLicenseManager()->findLicensesByLanguage($languageService)
        ));
        $view->setTemplate('license/manage');
        return $view;
    }

    public function detailAction()
    {
        $view = new ViewModel(array(
            'license' => $this->getLicenseManager()->getLicense($this->params('id'))
        ));
        $view->setTemplate('license/detail');
        return $view;
    }

    public function updateAction()
    {
        $form = $this->getLicenseManager()->getLicenseForm($this->params('id'));
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('license/update');
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $this->getLicenseManager()->updateLicense($form);
                $this->getLicenseManager()
                    ->getObjectManager()
                    ->flush();
                $this->redirect()->toUrl($this->referer()
                    ->fromStorage());
                return false;
            }
        } else {
            $this->referer()->store();
        }
        return $view;
    }

    public function addAction()
    {
        $form = $this->getLicenseManager()->getLicenseForm();
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('license/add');
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $this->getLicenseManager()->addLicense($form, $this->getLanguageManager()
                    ->getLanguageFromRequest());
                $this->getLicenseManager()
                    ->getObjectManager()
                    ->flush();
                $this->redirect()->toUrl($this->referer()
                    ->fromStorage());
                return false;
            }
        } else {
            $this->referer()->store();
        }
        return $view;
    }

    public function removeAction()
    {
        $this->getLicenseManager()->removeLicense($this->params('id'));
        $this->getLicenseManager()
            ->getObjectManager()
            ->flush();
        $this->redirect()->toReferer();
        return false;
    }
}