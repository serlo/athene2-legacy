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
namespace Flag\Controller;

use Flag\Form\FlagForm;
use Flag\Manager\FlagManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FlagController extends AbstractActionController
{
    use FlagManagerAwareTrait;

    public function addAction()
    {
        $this->assertGranted('flag.create');

        $this->layout('layout/1-col');
        $types = $this->getFlagManager()->findAllTypes();
        $form  = new FlagForm($types);
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $data     = $form->getData();
                $uuid     = $this->params('id');
                $this->getFlagManager()->addFlag((int)$data['type'], $data['content'], $uuid);
                $this->getFlagManager()->flush();
                $this->flashMessenger()->addSuccessMessage('The content has been flagged.');
                return $this->redirect()->toUrl($this->referer()->fromStorage());
            }
        } else {
            $this->referer()->store();
        }

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('flag/add');

        return $view;
    }

    public function detailAction()
    {
        $id   = (int)$this->params('id');
        $flag = $this->getFlagManager()->getFlag($id);
        $view = new ViewModel(['flag' => $flag]);
        $view->setTemplate('flag/detail');
        return $view;
    }

    public function manageAction()
    {
        $flags = $this->getFlagManager()->findAllFlags();
        $view  = new ViewModel(['flags' => $flags]);
        $view->setTemplate('flag/manage');
        return $view;
    }

    public function removeAction()
    {
        $id = $this->params('id');
        $this->getFlagManager()->removeFlag((int)$id);
        $this->getFlagManager()->flush();
        $this->flashMessenger()->addSuccessMessage('Your action was successfull.');
        return $this->redirect()->toReferer();
    }
}