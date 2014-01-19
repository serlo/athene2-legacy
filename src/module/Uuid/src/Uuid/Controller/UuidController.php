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
namespace Uuid\Controller;

use Uuid\Manager\UuidManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UuidController extends AbstractActionController
{
    use UuidManagerAwareTrait;

    public function recycleBinAction()
    {
        $entities = $this->getUuidManager()->findByTrashed(true);
        $data     = [
            'entities' => $entities
        ];
        $view     = new ViewModel($data);

        $view->setTemplate('uuid/recycle-bin');

        return $view;
    }

    public function trashAction()
    {
        $id   = $this->params('id');
        $uuid = $this->getUuidManager()->trashUuid($id);

        $this->getUuidManager()->flush();

        $this->redirect()->toReferer();

        return null;
    }

    public function restoreAction()
    {
        $id = $this->params('id');

        $this->getUuidManager()->restoreUuid($id);
        $this->getUuidManager()->flush();

        $this->redirect()->toReferer();

        return null;
    }

    public function purgeAction()
    {
        $id = $this->params('id');

        $this->getUuidManager()->purgeUuid($id);
        $this->getUuidManager()->getObjectManager()->flush();
        $this->redirect()->toReferer();

        return null;
    }
}
