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

use Attachment\Entity\AttachmentInterface;
use Attachment\Form\AttachmentForm;
use Attachment\Manager\AttachmentManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AttachmentController extends AbstractActionController
{

    use AttachmentManagerAwareTrait;

    public function infoAction()
    {
        $attachment = $this->getAttachmentManager()->getAttachment($this->params('id'));

        return $this->createJsonResponse($attachment);
    }

    public function fileAction()
    {
        $upload = $this->getAttachmentManager()->getFile($this->params('id'), $this->params('file'));
        $this->redirect()->toUrl($upload->getLocation());

        return false;
    }

    public function attachAction()
    {
        $form = new AttachmentForm();

        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('upload/form');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getFiles()->toArray();

            $form->setData($post);
            if ($form->isValid()) {
                $data       = $form->getData();
                $attachment = $this->getAttachmentManager()->attach($data['file'], $this->params('append'));
                $this->getAttachmentManager()->getObjectManager()->flush();

                return $this->createJsonResponse($attachment);
            }
        }

        return $view;
    }

    protected function createJsonResponse(AttachmentInterface $attachment)
    {

        foreach ($attachment->getFiles() as $file) {
            $url     = $this->url()->fromRoute(
                'attachment/file',
                ['id' => $attachment->getId(), 'file' => $file->getId()]
            );
            $files[] = [
                'location' => $url,
                'size'     => $file->getSize(),
                'id'       => $file->getId(),
                'type'     => $file->getType(),
                'filename' => $file->getFilename()
            ];
        }

        return new JsonModel(array(
            'success' => true,
            'id'      => $attachment->getId(),
            'files'   => $files
        ));
    }
}