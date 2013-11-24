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
namespace Uuid\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UuidController extends AbstractActionController
{
    use\Uuid\Manager\UuidManagerAwareTrait,\User\Manager\UserManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    public function recycleBinAction()
    {
        $entities = $this->getUuidManager()->findByTrashed(true);
        $view = new ViewModel(array(
            'entities' => $entities
        ));
        $view->setTemplate('uuid/recycle-bin');
        return $view;
    }

    public function trashAction()
    {
        $id = $this->params('id');
        $uuid = $this->getUuidManager()->getUuid($id);
        $uuid->setTrashed(true);
        
        $this->getEventManager()->trigger('trash', $this, array(
            'user' => $this->getUserManager()
                ->getUserFromAuthenticator(),
            'object' => $uuid,
            'language' => $this->getLanguageManager()
                ->getLanguageFromRequest()
        ));
        
        $this->getUuidManager()
            ->getObjectManager()
            ->flush();
        $this->redirect()->toReferer();
        return false;
    }

    public function restoreAction()
    {
        $id = $this->params('id');
        $uuid = $this->getUuidManager()->getUuid($id);
        $uuid->setTrashed(false);
        
        $this->getEventManager()->trigger('restore', $this, array(
            'user' => $this->getUserManager()
                ->getUserFromAuthenticator(),
            'object' => $uuid,
            'language' => $this->getLanguageManager()
                ->getLanguageFromRequest()
        ));
        
        $this->getUuidManager()
            ->getObjectManager()
            ->flush();
        $this->redirect()->toReferer();
        return false;
    }

    public function purgeAction()
    {
        $id = $this->params('id');
        $uuid = $this->getUuidManager()->getUuid($id);
        $this->getUuidManager()
            ->getObjectManager()
            ->remove($uuid);
        $this->getUuidManager()
            ->getObjectManager()
            ->flush();
        $this->redirect()->toReferer();
        return false;
    }
}