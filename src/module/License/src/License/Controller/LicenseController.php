<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace License\Controller;

use Instance\Manager\InstanceManagerAwareTrait;
use License\Manager\LicenseManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LicenseController extends AbstractActionController
{
    use LicenseManagerAwareTrait, InstanceManagerAwareTrait;

    public function manageAction()
    {
        $instanceService = $this->getInstanceManager()->getTenantFromRequest();
        $this->assertGranted('licenses.manage', $instanceService);

        $view = new ViewModel(array(
            'licenses' => $this->getLicenseManager()->findLicensesByLanguage($instanceService)
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
        $license = $this->getLicenseManager()->getLicense($this->params('id'));
        $this->assertGranted('license.update', $license);

        $form = $this->getLicenseManager()->getLicenseForm($this->params('id'));
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('license/update');
        if ($this->getRequest()->isPost()) {
            $form->setData(
                $this->getRequest()->getPost()
            );
            if ($form->isValid()) {
                $this->getLicenseManager()->updateLicense($form);
                $this->getLicenseManager()->getObjectManager()->flush();
                $this->redirect()->toUrl(
                    $this->referer()->fromStorage()
                );

                return false;
            }
        } else {
            $this->referer()->store();
        }

        return $view;
    }

    public function addAction()
    {
        $this->assertGranted('license.create');

        $form = $this->getLicenseManager()->getLicenseForm();
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('license/add');
        if ($this->getRequest()->isPost()) {
            $form->setData(
                $this->getRequest()->getPost()
            );
            if ($form->isValid()) {
                $this->getLicenseManager()->addLicense(
                    $form,
                    $this->getInstanceManager()->getTenantFromRequest()
                );
                $this->getLicenseManager()->getObjectManager()->flush();
                $this->redirect()->toUrl(
                    $this->referer()->fromStorage()
                );

                return false;
            }
        } else {
            $this->referer()->store();
        }

        return $view;
    }

    public function removeAction()
    {
        $license = $this->getLicenseManager()->getLicense($this->params('id'));
        $this->assertGranted('license.purge', $license);

        $this->getLicenseManager()->removeLicense($this->params('id'));
        $this->getLicenseManager()->getObjectManager()->flush();
        $this->redirect()->toReferer();

        return false;
    }
}