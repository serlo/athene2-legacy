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
namespace Attachment\Controller;

use Attachment\Form\AttachmentForm;
use Attachment\Manager\AttachmentManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AttachmentController extends AbstractActionController
{

    use AttachmentManagerAwareTrait;

    public function getAction()
    {
        $upload = $this->getAttachmentManager()->getAttachment((int)$this->params('id'));
        $this->redirect()->toUrl($upload->getLocation());

        return false;
    }

    public function attachAction()
    {
        $form = new AttachmentForm();
        $form->setAttribute('action', $this->url()->fromRoute('upload'));
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('upload/form');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getFiles()->toArray();

            $form->setData($post);
            if ($form->isValid()) {
                $data   = $form->getData();
                $upload = $this->getAttachmentManager()->attach($data['file']);
                $this->getAttachmentManager()->getObjectManager()->flush();

                return new JsonModel(array(
                    'success'  => true,
                    'location' => $this->url()->fromRoute('upload/get', array('id' => $upload->getId())),
                    'size'     => $upload->getSize(),
                    'id'       => $upload->getId(),
                    'type'     => $upload->getType(),
                    'filename' => $upload->getFilename()
                ));
            }
        }

        return $view;
    }
}